<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/entreprise")
 */
class EntreprisesController extends AbstractController
{
    /**
     * @Route("/", name="entreprises")
     */
    public function index(): Response
    {
        return $this->render('entreprises/index.html.twig', );
    }
}
