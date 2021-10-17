<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function create(FormFactoryInterface $factory, Request $request, EntityManagerInterface $em): Response
    {

        // Form d'ajout & utilisation de data_class
        $builder = $factory->createBuilder(FormType::class, null, [
            'data_class' => Skill::class
        ]);
        
        // Les champs
        $builder->setMethod('POST')
            ->add('name', TextType::class, [
                'label' => 'Nom de la compétence',
                'attr' => [
                    'placeholder' => 'Tapez le nom de la compétence'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter la compétence'
            ]);

        // On récupère le form ( mias la classe est énorme )
        $form = $builder->getForm();
        

        // Regarde dans la request si quelque chose t'intéresse
        $form->handleRequest($request);

        // Si le form est soumis et valid, flush et redirection
        if ($form->isSubmitted()) {

            $skill = $form->getData();
            $skill->setCreatedAt(new \DateTime());

            $em->persist($skill);
            $em->flush();
            
            return $this->redirectToRoute('skills', [], Response::HTTP_SEE_OTHER);
        }

    

        // Récupère juste la partie visualisation
        $formView = $form->createView();

        return $this->render('skills/create.html.twig', [
            'formView' => $formView
        ]);
    }
}

