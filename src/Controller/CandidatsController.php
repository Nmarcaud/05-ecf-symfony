<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Status;
use App\Form\CandidatType;
use App\Repository\UserRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/candidats")
 */

class CandidatsController extends AbstractController
{
    /**
     * @Route("/", name="candidats")
     */
    public function index(UserRepository $userRepository, StatusRepository $statusRepository): Response
    {
        // Retrouve l'id "Candidat"
        $idCandidat = $statusRepository->findBy(['name' => 'Candidat']);

        // Liste des candidats
        $listCandidats = $userRepository->findBy(['status' => $idCandidat]);
        return $this->render('candidats/index.html.twig', [
            "candidats" => $listCandidats
        ]);
    }


    /**
     * @Route("/create", name="candidats_create")
     */
    public function create(Request $request, EntityManagerInterface $em, StatusRepository $statusRepository): Response
    {
        $candidat = new User;
        $form = $this->createForm(CandidatType::class, $candidat);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            // Récupération de la bonne entité candidat
            $status = $statusRepository->findBy(["name" => "Candidat"]);

            $candidat
                ->setCreatedAt(new \DateTime())
                ->setStatus($status[0]);        // Premier ( et seul ) résultat retourné
                //->setRoles();                 // null dans le cas de candidat

            $em->persist($candidat);
            $em->flush();
            
            return $this->redirectToRoute('candidats', [], Response::HTTP_SEE_OTHER);
        }
 
        return $this->render('candidats/create.html.twig', [
            'formView' => $formView
        ]);
    }


    /**
     * @Route("/{id}/edit", name="candidats_edit")
     */
    public function edit($id, UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    {
        $candidat = $userRepository->find($id);

        $form = $this->createForm(CandidatType::class, $candidat);    // 1 Création du formulaire,AVEC l'élément à modifier
        $formView = $form->createView();                        // 2 Création de la vue

        $form->handleRequest($request);                         // 3 Inspecte la request ( si form soumis )
        if ($form->isSubmitted()) {                             // 4 Si, est soumis

            $candidat->setModifiedAt(new \DateTime());             // 5 Datetime de modification
            $em->flush();                                       // 6 Pas besoin de persist car déjà en base
            
            return $this->redirectToRoute('candidats', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('candidats/edit.html.twig', [
            'formView' => $formView,
            'candidat' => $candidat
        ]);
    }

    /**
     * @Route("/{id}/delete", name="candidats_delete")
     */
    public function delete($id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $candidat = $userRepository->find($id);
        $em->remove($candidat);
        $em->flush();
        
        return $this->redirectToRoute('candidats', [], Response::HTTP_SEE_OTHER);
    }
}
