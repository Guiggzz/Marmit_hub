<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\RecetteIngredient;
use App\Form\RecetteType;
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

        // Initialisation d'un ingrédient vide pour afficher un champ dans le formulaire
        $recetteIngredient = new RecetteIngredient();
        $recetteIngredient->setRecette($recette);
        $recette->addRecetteIngredient($recetteIngredient);

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
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $recette = $em->getRepository(Recette::class)->find($id);
        if (!$recette) {
            throw $this->createNotFoundException('Recette non trouvée.');
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }
}
