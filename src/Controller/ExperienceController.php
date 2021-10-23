<?php

namespace App\Controller;

use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExperienceController extends AbstractController
{

    protected $experienceRepository;
    protected $em;

    public function __construct(ExperienceRepository $experienceRepository, EntityManagerInterface $em)
    {
        $this->experienceRepository = $experienceRepository;
        $this->em = $em;
    }
    /**
     * @Route("/experience", name="experience")
     */
    public function index(): Response
    {
        return $this->render('experience/index.html.twig', [
            'controller_name' => 'ExperienceController',
        ]);
    }


    /**
     * @Route("/{id}/edit", name="experience_edit")
     */
    // public function edit($id, UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    // {
    //     $candidat = $userRepository->find($id);

    //     $form = $this->createForm(CandidatType::class, $candidat);    // 1 Création du formulaire,AVEC l'élément à modifier
    //     $formView = $form->createView();                        // 2 Création de la vue

    //     $form->handleRequest($request);                         // 3 Inspecte la request ( si form soumis )
    //     if ($form->isSubmitted()) {                             // 4 Si, est soumis

    //         $candidat->setModifiedAt(new \DateTime());             // 5 Datetime de modification
    //         $em->flush();                                       // 6 Pas besoin de persist car déjà en base
            
    //         return $this->redirectToRoute('candidats', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('experience/edit.html.twig', [
    //         'formView' => $formView,
    //         'candidat' => $candidat
    //     ]);
    // }

    /**
     * @Route("/{id}/delete", name="experience_delete")
     */
    public function delete($id): Response
    {
        $experience = $this->experienceRepository->find($id);

        // Je chope le user id avent supression pour redirect
        $userIdToRedirect = $experience->getUser()->getId();

        $this->em->remove($experience);
        $this->em->flush();
        
        return $this->redirect($this->generateUrl('profil', array('id' => $userIdToRedirect)));
    }
}
