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
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('recettes_photos_directory'),
                        $newFilename
                    );
                    $recette->setPhoto($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo');
                }
            }

            $recette->setUtilisateur($this->getUser());
            $entityManager->persist($recette);
            $entityManager->flush();

            $this->addFlash('success', 'Recette créée avec succès !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('recette/nouvelle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recette/{id}', name: 'recette_show')]
    public function show(Recette $recette): Response
    {
        if (!$recette) {
            throw $this->createNotFoundException('La recette n\'existe pas');
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }
}