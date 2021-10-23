<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/entreprises")
 */
class EntreprisesController extends AbstractController
{
    /**
     * @Route("/", name="entreprises")
     * @Security("is_granted('ROLE_ADMIN')")
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
