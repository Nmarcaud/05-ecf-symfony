<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("/{id}/delete", name="collaborateurs_delete")
     */
    public function delete($id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);
        $em->remove($user);
        $em->flush();
        
        return $this->redirectToRoute('collaborateurs', [], Response::HTTP_SEE_OTHER);
    }
}
