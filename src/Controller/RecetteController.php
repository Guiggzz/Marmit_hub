<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\Ingredient; // Assurez-vous d'inclure l'entité Ingredient
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
    #[Route('/recette/nouvelle', name: 'app_recette_nouvelle')]
    public function nouvelleRecette(
        Request $request, 
        EntityManagerInterface $entityManager, 
        SluggerInterface $slugger
    ): Response {
        // Créer une nouvelle instance de recette
        $recette = new Recette();
        
        // Récupérer la liste des ingrédients depuis la base de données
        $ingredients = $entityManager->getRepository(Ingredient::class)->findAll();

        // Créer le formulaire
        $form = $this->createForm(RecetteType::class, $recette, [
            'ingredients' => $ingredients, // Passer les ingrédients au formulaire
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du téléchargement de photo
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $extension = $photoFile->guessExtension();

                $newFilename = $safeFilename.'-'.uniqid().'.'.$extension;

                try {
                    $photoFile->move(
                        $this->getParameter('recettes_photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo');
                }

                $recette->setPhoto($newFilename);
            }

            // Associer l'utilisateur connecté
            $recette->setUtilisateur($this->getUser());

            // Enregistrer la recette en base de données
            $entityManager->persist($recette);
            $entityManager->flush();

            // Ajouter les ingrédients associés à la recette
            $ingredients = $form->get('ingredients')->getData(); // Récupérer les ingrédients sélectionnés
            foreach ($ingredients as $ingredient) {
                $recette->addIngredient($ingredient); // Ajouter chaque ingrédient à la recette
            }

            $entityManager->flush();

            $this->addFlash('success', 'Votre recette a été créée avec succès !');
            return $this->redirectToRoute('app_home');
        }

        // Passer les ingrédients à la vue
        return $this->render('recette/nouvelle.html.twig', [
            'form' => $form->createView(),
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/recette/{id}', name: 'recette_show')]
    public function show(Recette $recette): Response
    {
        // Vérifier si la recette existe
        if (!$recette) {
            throw $this->createNotFoundException('La recette n\'existe pas');
        }

        // Rendre la vue avec la recette
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }
}