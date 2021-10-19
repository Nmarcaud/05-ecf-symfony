<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/create", name="skills_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $skill = new Skill;                                                 // 1 Création d'un objet vide
        $form = $this->createForm(SkillType::class, $skill);                // 1 Création du formulaire
        $formView = $form->createView();                                    // 3 Création de la vue

        $form->handleRequest($request);                                     // 4 Inspecte la request ( si form soumis )
        if ($form->isSubmitted()) {                                         // 5 Si, est soumis

            $skill->setCreatedAt(new \DateTime());                          // 6 Datetime de création

            $em->persist($skill);                                           // 7 Persist les données ( avec em )
            $em->flush();                                                   // 8 Flush ( avec em )
            
            return $this->redirectToRoute('skills', [], Response::HTTP_SEE_OTHER);
        }
 
        return $this->render('skills/create.html.twig', [
            'formView' => $formView
        ]);
    }


    /**
     * @Route("/{id}/edit", name="skills_edit")
     */
    public function edit($id, SkillRepository $skillRepository, Request $request, EntityManagerInterface $em): Response
    {
        $skill = $skillRepository->find($id);

        $form = $this->createForm(SkillType::class, $skill);    // 1 Création du formulaire,AVEC l'élément à modifier
        $formView = $form->createView();                        // 2 Création de la vue

        $form->handleRequest($request);                         // 3 Inspecte la request ( si form soumis )
        if ($form->isSubmitted()) {                             // 4 Si, est soumis

            $skill->setModifiedAt(new \DateTime());             // 5 Datetime de modification
            $em->flush();                                       // 6 Pas besoin de persist car déjà en base
            
            return $this->redirectToRoute('skills', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('skills/edit.html.twig', [
            'formView' => $formView,
            'skill' => $skill
        ]);
    }


    /**
     * @Route("/{id}/delete", name="skills_delete")
     */
    public function delete($id, SkillRepository $skillRepository, EntityManagerInterface $em): Response
    {
        $skill = $skillRepository->find($id);
        $em->remove($skill);
        $em->flush();
        
        return $this->redirectToRoute('skills', [], Response::HTTP_SEE_OTHER);
    }
}

