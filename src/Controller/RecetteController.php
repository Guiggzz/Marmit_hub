<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\RecetteIngredient;
use App\Entity\Commentaire;
use App\Form\RecetteType;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RecetteController extends AbstractController
{
    #[Route('/recette/nouvelle', name: 'app_recette_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $recette = new Recette();

        // Associer l'utilisateur connecté
        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            return $this->redirectToRoute('app_login');
        }
        $recette->setUtilisateur($utilisateur);

        // Création et traitement du formulaire
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de la photo
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('recettes_photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('app_recette_create');
                }

                $recette->setPhoto($newFilename);
            }

            // Gérer manuellement les RecetteIngredients si nécessaire
            $ingredientsData = $form->get('ingredients')->getData();
            foreach ($ingredientsData as $ingredient) {
                $recetteIngredient = new RecetteIngredient();
                $recetteIngredient->setRecette($recette);
                $recetteIngredient->setIngredient($ingredient);
                $recette->addRecetteIngredient($recetteIngredient);
                $em->persist($recetteIngredient);
            }

            // Enregistrement de la recette et des ingrédients
            $em->persist($recette);
            $em->flush();

            $this->addFlash('success', 'La recette a été créée avec succès.');
            return $this->redirectToRoute('recette_show', ['id' => $recette->getId()]);
        }

        return $this->render('recette/nouvelle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recette/{id}', name: 'recette_show')]
    public function show(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $recette = $em->getRepository(Recette::class)->find($id);
        if (!$recette) {
            throw $this->createNotFoundException('Recette non trouvée.');
        }

        // Création d'un formulaire pour le commentaire
        // Lors de la création d'un commentaire dans le contrôleur
        $commentaire = new Commentaire();
        $commentaire->setRecette($recette);
        $commentaire->setUtilisateur($this->getUser());  // Lier l'utilisateur connecté au commentaire
        $commentaire->setDate(new \DateTime());

        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
        $formCommentaire->handleRequest($request);

        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $em->persist($commentaire);
            $em->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès.');
            return $this->redirectToRoute('recette_show', ['id' => $recette->getId()]);
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'formCommentaire' => $formCommentaire->createView(),
        ]);
    }

    #[Route('/recette/supprimer/{id}', name: 'recette_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $recette = $em->getRepository(Recette::class)->find($id);

        if (!$recette) {
            throw $this->createNotFoundException('Recette non trouvée.');
        }

        // Vérification : l'utilisateur connecté est-il l'auteur ?
        if ($recette->getUtilisateur() !== $this->getUser()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à supprimer cette recette.');
            return $this->redirectToRoute('app_home');
        }

        // Supprimer la recette
        $em->remove($recette);
        $em->flush();

        $this->addFlash('success', 'La recette a été supprimée avec succès.');

        return $this->redirectToRoute('app_home');
    }
    #[Route('/commentaire/ajouter/{recetteId}', name: 'commentaire_add', methods: ['POST'])]
    public function add(int $recetteId, Request $request, EntityManagerInterface $em): Response
    {
        $recette = $em->getRepository(Recette::class)->find($recetteId);
        if (!$recette) {
            throw $this->createNotFoundException('Recette non trouvée.');
        }

        $commentaire = new Commentaire();
        $commentaire->setRecette($recette);
        $commentaire->setTexte($request->request->get('texte'));
        $commentaire->setDate(new \DateTime());

        $em->persist($commentaire);
        $em->flush();

        $this->addFlash('success', 'Commentaire ajouté avec succès.');

        return $this->redirectToRoute('recette_show', ['id' => $recetteId]);
    }
}
