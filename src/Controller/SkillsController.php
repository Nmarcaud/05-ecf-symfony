<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin/skills")
 */
class SkillsController extends AbstractController
{
    /**
     * @Route("/", name="skills")
     */
    public function index(SkillRepository $skillRepository): Response
    {
        // Nombre de valeurs
        $count = $skillRepository->count([]);
        // Liste des skills
        $listSkills = $skillRepository->findAll();

        return $this->render('skills/index.html.twig', [
            "skills" => $listSkills
        ]);
    }

    /**
     * @Route("/add", name="add_skills")
     */
    public function add(EntityManagerInterface $em): Response
    {

        // TO DO - Ajout d'un form d'ajout 

        return $this->render('skills/add.html.twig');
    }
}
