<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/entreprises")
 */
class EntreprisesController extends AbstractController
{
    /**
     * @Route("/", name="entreprises")
     */
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {

        // Liste des entreprises
        $listEntreprises = $entrepriseRepository->findAll();
        return $this->render('entreprises/index.html.twig', [
            "entreprises" => $listEntreprises
        ]);

        return $this->render('entreprises/index.html.twig', );
    }
}
