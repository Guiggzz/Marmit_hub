<?php

namespace App\Controller;

use App\Entity\Recette;
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
    public function create(Request $request, EntityManagerInterface $em)
    {
        // Créer une nouvelle recette
        $recette = new Recette();

        // Créer le formulaire
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les recetteIngredients du formulaire
            $recetteIngredients = $form->get('recetteIngredients')->getData();

            // Enregistrer la recette dans la base de données
            $em->persist($recette);

            // Enregistrer chaque ingrédient dans la table recette_ingredient avec la quantité
            foreach ($recetteIngredients as $recetteIngredient) {
                $recetteIngredient->setRecette($recette); // Associer l'ingrédient à la recette
                $em->persist($recetteIngredient); // Enregistrer chaque RecetteIngredient
            }

            // Sauvegarder les changements
            $em->flush();

            // Rediriger ou afficher un message de succès
            return $this->redirectToRoute('app_recette_success');
        }

        return $this->render('recette/nouvelle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recette/{id}', name: 'recette_show')]
    public function show($id, EntityManagerInterface $em)
    {
        $recette = $em->getRepository(Recette::class)->find($id);
        if (!$recette) {
            throw $this->createNotFoundException('Recette non trouvée');
        }
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }
}
