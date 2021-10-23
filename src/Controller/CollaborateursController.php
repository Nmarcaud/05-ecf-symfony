<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\StatusRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/collaborateurs")
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMMERCIAL')")
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
