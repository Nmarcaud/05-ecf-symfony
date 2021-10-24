<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CandidatType;
use App\Repository\UserRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/candidats")
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMMERCIAL')")
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
