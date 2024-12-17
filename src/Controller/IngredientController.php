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
    #[Route('/ingredient/{id}', name: 'app_ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }
    #[Route('/ingredients', name: 'app_ingredients_list', methods: ['GET'])]
    public function listIngredients(EntityManagerInterface $entityManager): Response
    {
        // Récupère tous les ingrédients depuis la base de données
        $ingredients = $entityManager->getRepository(Ingredient::class)->findAll();

        return $this->render('ingredient/ingredient_show.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }
    #[Route('/ingredient/{id}', name: 'ingredient_delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, Ingredient $ingredient, EntityManagerInterface $em): Response
    {
        // Vérifier que l'utilisateur est bien le créateur
        if ($this->getUser() !== $ingredient->getUtilisateur()) {
            throw $this->createAccessDeniedException("Vous n'êtes pas autorisé à supprimer cet ingrédient.");
        }

        // Vérifier que l'ingrédient n'est pas utilisé dans des recettes
        if (count($ingredient->getRecetteIngredients()) > 0) {
            $this->addFlash('error', 'Cet ingrédient est lié à des recettes et ne peut pas être supprimé.');
            return $this->redirectToRoute('ingredient_show', ['id' => $ingredient->getId()]);
        }

        // Vérification CSRF
        if ($this->isCsrfTokenValid('delete' . $ingredient->getId(), $request->request->get('_token'))) {
            $em->remove($ingredient);
            $em->flush();
            $this->addFlash('success', 'Ingrédient supprimé avec succès.');
        }

        return $this->redirectToRoute('app_home');
    }
}
