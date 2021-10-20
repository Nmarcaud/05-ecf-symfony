<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("{id}/profil", name="profil")
     */
    public function index($id, UserRepository $userRepository): Response
    {

        // Candidat ou Collaborateur
        $profil = $userRepository->find($id);

        return $this->render('profil/index.html.twig', [
            'profil' => $profil,
        ]);
    }
}
