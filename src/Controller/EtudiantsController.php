<?php

namespace App\Controller;

use App\Entity\Etudiants;
use App\Form\EtudiantsType;
use App\Repository\EtudiantsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use DateTime;
use DateTimeZone;

#[Route('/etudiants')]
class EtudiantsController extends AbstractController
{
    #[Route('/', name: 'app_etudiants_index', methods: ['GET'])]
    public function index(EtudiantsRepository $etudiantsRepository): Response
    {


        return $this->render('etudiants/index.html.twig', [
            'etudiants' => $etudiantsRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_etudiants_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EtudiantsRepository $etudiantsRepository, SluggerInterface $slugger): Response
    {
        $etudiant = new Etudiants();
        $form = $this->createForm(EtudiantsType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $today = new \DateTimeImmutable('now');
            //$etudiant->setDateInscription($today);
            $today = new DateTime('now', new DateTimeZone('Europe/Paris'));
            $etudiant->setDateInscription($today);
            $fichier = $form->get('document')->getData();
            if ($fichier) {
                // récupérer le nom du fichier
                $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                //déplacer le fichier dans le dossier publi/uploads avec le nom original
                $safeFilename = $slugger->slug($originalFilename);
                $nomfichier = $safeFilename . '.' . $fichier->guessExtension();
                // // Move the file to the directory where brochures are stored
                $fichier->move(
                    $this->getParameter('fileDirectory'),
                    $nomfichier
                );
                //ajouter le nom du fichier dans $article
                $etudiant->setDocument($nomfichier);
            }
            $etudiantsRepository->save($etudiant, true);

            return $this->redirectToRoute('app_etudiants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiants/new.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etudiants_show', methods: ['GET'])]
    public function show(Etudiants $etudiant): Response
    {
        return $this->render('etudiants/show.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etudiants_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etudiants $etudiant, EtudiantsRepository $etudiantsRepository, SluggerInterface $slugger): Response
    {
        $s = $etudiant->getDocument();
        $form = $this->createForm(EtudiantsType::class, $etudiant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $fichier = $form->get('document')->getData();
            if ($fichier) {
                $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                // ajouter l'extension sur le fichier
                $nomfichier = $safeFilename . '.' . $fichier->guessExtension();
                // // Move the file to the directory where brochures are stored
                $fichier->move(
                    $this->getParameter('fileDirectory'),
                    $nomfichier
                );

                $etudiant->setDocument($nomfichier);
            } else {
                $etudiant->setDocument($s);
            }
            $etudiantsRepository->save($etudiant, true);

            return $this->redirectToRoute('app_etudiants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiants/edit.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etudiants_delete', methods: ['POST'])]
    public function delete(Request $request, Etudiants $etudiant, EtudiantsRepository $etudiantsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $etudiant->getId(), $request->request->get('_token'))) {
            $etudiantsRepository->remove($etudiant, true);
        }

        return $this->redirectToRoute('app_etudiants_index', [], Response::HTTP_SEE_OTHER);
    }
}
