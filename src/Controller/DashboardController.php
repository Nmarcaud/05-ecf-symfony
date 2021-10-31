<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{

    protected $userRepository;
    protected $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {

        // 1. Nouveaux Collaborateur ( -1 mois )
        $conn = $this->em->getConnection();

        $sql = '
            SELECT u.id, u.firstname, u.lastname, u.picture_url, u.disponibility, u.job_title FROM user AS u
            LEFT JOIN status AS s ON u.status_id = s.id
            WHERE u.created_at > DATE_ADD(NOW(), INTERVAL -1 MONTH)
            AND s.name = "Collaborateur"
            ORDER BY u.created_at DESC
            ';
        $stmt = $conn->executeQuery($sql);

        $listNewCollaborateur = $stmt->fetchAllAssociative();


        // 2. Collaborateurs Modifiés
        $conn = $this->em->getConnection();

        $sql = '
            SELECT u.id, u.firstname, u.lastname, u.picture_url, u.disponibility, u.job_title FROM user AS u
            RIGHT JOIN status AS s ON u.status_id = s.id
            WHERE u.modified_at > DATE_ADD(NOW(), INTERVAL -1 MONTH)
            AND s.name = "Collaborateur"
            ORDER BY u.modified_at DESC
            ';
        $stmt = $conn->executeQuery($sql);

        $listModifiedCollaborateur = $stmt->fetchAllAssociative();


        // 3 Nouveaux Candidats
        $conn = $this->em->getConnection();

        $sql = '
            SELECT u.id, u.firstname, u.lastname, u.picture_url, u.disponibility, u.job_title FROM user AS u
            LEFT JOIN status AS s ON u.status_id = s.id
            WHERE u.created_at > DATE_ADD(NOW(), INTERVAL -1 MONTH)
            AND s.name = "Candidat"
            ORDER BY u.created_at DESC
            ';
        $stmt = $conn->executeQuery($sql);

        $listNewCandidat = $stmt->fetchAllAssociative();


        return $this->render('dashboard/index.html.twig', [
            'listNewCollaborateur' => $listNewCollaborateur,
            'listModifiedCollaborateur' => $listModifiedCollaborateur,
            'listNewCandidat' => $listNewCandidat
        ]);
    }
}
