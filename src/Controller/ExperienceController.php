<?php

namespace App\Controller;

use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/experience")
 */
class ExperienceController extends AbstractController
{

    protected $experienceRepository;
    protected $em;
    protected $userRepository;

    public function __construct(ExperienceRepository $experienceRepository, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->experienceRepository = $experienceRepository;
        $this->em = $em;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/", name="experience")
     */
    public function index(): Response
    {
        return $this->render('experience/index.html.twig', [
            'controller_name' => 'ExperienceController',
        ]);
    }


    /**
     * @Route("/{id_exp}/edit", name="experience_edit")
     */
    public function edit($id_exp, Request $request): Response
    {
        $experience = $this->experienceRepository->find($id_exp);

        $form = $this->createForm(ExperienceType::class, $experience);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            // Je chope le user id avent supression pour redirect
            $userIdToRedirect = $experience->getUser()->getId();

            // Update du User
            $profil = $this->userRepository->find($userIdToRedirect);
            $profil->setModifiedAt(new \DateTime());

            $experience->setModifiedAt(new \DateTime());
            $this->em->flush();
            
            return $this->redirect($this->generateUrl('profil', array('id' => $userIdToRedirect)));
        }

        return $this->render('experience/edit.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/{id_exp}/delete", name="experience_delete")
     */
    public function delete($id_exp): Response
    {
        $experience = $this->experienceRepository->find($id_exp);

        // Je chope le user id avent supression pour redirect
        $userIdToRedirect = $experience->getUser()->getId();

        // Update du User
        $profil = $this->userRepository->find($userIdToRedirect);
        $profil->setModifiedAt(new \DateTime());

        $this->em->remove($experience);
        $this->em->flush();
        
        return $this->redirect($this->generateUrl('profil', array('id' => $userIdToRedirect)));
    }
}
