<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\StatusRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin/collaborateurs")
 */
class CollaborateursController extends AbstractController
{
    /**
     * @Route("/", name="collaborateurs")
     */
    public function index(UserRepository $userRepository, StatusRepository $statusRepository): Response
    {
        // Retrouve l'id "Collaborateur"
        $idCollaborateur = $statusRepository->findBy(['name' => 'Collaborateur']);

        // Liste des collaborateur
        $listCollaborateurs = $userRepository->findBy(['status' => $idCollaborateur]);
        return $this->render('collaborateurs/index.html.twig', [
            "collaborateurs" => $listCollaborateurs
        ]);

        return $this->render('collaborateur/index.html.twig', [
            'controller_name' => 'CollaborateurController',
        ]);
    }
}
