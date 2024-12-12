<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecetteController extends AbstractController
{
    #[Route('/recette/nouvelle', name: 'app_recette_nouvelle')]
    public function nouvelleRecette(
        Request $request, 
        EntityManagerInterface $entityManager, 
        SluggerInterface $slugger
    ): Response {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            $entityManager->persist($recette);
            $entityManager->flush();

            $this->addFlash('success', 'Votre recette a été créée avec succès !');

            // Redirection vers la page de la recette nouvellement créée
            return $this->redirectToRoute('recette_show', ['id' => $recette->getId()]);
        }

        return $this->render('recette/nouvelle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Nouvelle méthode pour afficher la fiche d'une recette spécifique
    #[Route('/recette/{id}', name: 'recette_show')]
public function show(Recette $recette): Response
{
    // Récupération de l'utilisateur qui a créé la recette
    $utilisateur = $recette->getUtilisateur(); 

    return $this->render('recette/show.html.twig', [
        'recette' => $recette,
        'utilisateur' => $utilisateur, // Passer l'utilisateur à la vue
    ]);
}}