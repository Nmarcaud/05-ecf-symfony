<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/collaborateur", name="collaborateur-index")
     */
    public function index(): Response
    {
        return $this->render('collaborateur/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
