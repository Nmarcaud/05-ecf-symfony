<?php

namespace App\Controller;

use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/add", name="add_candidats")
     */
    public function add()
    {
        // TO DO - Form
    }
}
