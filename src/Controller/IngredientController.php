<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Entity\RecetteIngredient;
use App\Form\IngredientType;
use App\Form\RecetteIngredientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class IngredientController extends AbstractController
{
    #[Route('/creation/ingredient', name: 'app_ingredient_nouvelle')]
    public function nouvelleIngredient(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $extension = $photoFile->guessExtension();

                $newFilename = $safeFilename . '-' . uniqid() . '.' . $extension;

                try {
                    $photoFile->move(
                        $this->getParameter('ingredients_photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo');
                }

                $ingredient->setPhoto($newFilename);
            }

            // Associer l'utilisateur connecté
            $ingredient->setUtilisateur($this->getUser());;

            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash('success', 'Votre ingrédient a été créé avec succès !');
            return $this->redirectToRoute('app_home', ['id' => $ingredient->getId()]);
        }

        return $this->render('creation/ingredient.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ajouter-ingredient-a-recette/{recetteId}', name: 'app_ajouter_ingredient_recette')]
    public function ajouterIngredientRecette(
        Request $request,
        EntityManagerInterface $entityManager,
        Recette $recette
    ): Response {
        // Créer un nouvel objet RecetteIngredient
        $recetteIngredient = new RecetteIngredient();
        $form = $this->createForm(RecetteIngredientType::class, $recetteIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Lier l'ingrédient existant à la recette
            $ingredient = $form->get('ingredient')->getData(); // Assurez-vous que l'ingrédient est bien lié à ce formulaire
            $recetteIngredient->setIngredient($ingredient);
            $recetteIngredient->setRecette($recette);

            // Persist l'objet RecetteIngredient
            $entityManager->persist($recetteIngredient);
            $entityManager->flush();

            $this->addFlash('success', 'Ingrédient ajouté à la recette avec succès!');
            return $this->redirectToRoute('recette_show', ['id' => $recette->getId()]);
        }

        return $this->render('recette/ajouter_ingredient.html.twig', [
            'form' => $form->createView(),
            'recette' => $recette,
        ]);
    }
}
