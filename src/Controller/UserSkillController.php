<?php

namespace App\Controller;

use App\Form\UserSkillType;
use App\Repository\UserRepository;
use App\Repository\UserSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user/skill")
 */
class UserSkillController extends AbstractController
{

    protected $userSkillRepository;
    protected $em;
    protected $userRepository;

    public function __construct(UserSkillRepository $userSkillRepository, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->userSkillRepository = $userSkillRepository;
        $this->em = $em;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/", name="user_skill")
     */
    public function index(): Response
    {
        return $this->render('user_skill/index.html.twig', [
            'controller_name' => 'UserSkillController',
        ]);
    }

    /**
     * @Route("/{user_skill_id}/edit", name="user_skills_edit")
     */
    public function edit($user_skill_id, Request $request): Response
    {
        $userSkill = $this->userSkillRepository->find($user_skill_id);

        $form = $this->createForm(UserSkillType::class, $userSkill);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            // Je chope le user id avent supression pour redirect
            $userIdToRedirect = $userSkill->getUser()->getId();

            // Update du User
            $profil = $this->userRepository->find($userIdToRedirect);
            $profil->setModifiedAt(new \DateTime());

            $this->em->flush();
            
            return $this->redirect($this->generateUrl('profil', array('id' => $userIdToRedirect)));
        }

        return $this->render('user_skill/edit.html.twig', [
            'formView' => $formView
        ]);
    }
    /**
     * @Route("/{user_skill_id}/delete", name="user_skills_delete")
     */
    public function delete($user_skill_id): Response
    {
        $userSkill = $this->userSkillRepository->find($user_skill_id);
        
        // Je chope le user id avent supression pour redirect
        $userIdToRedirect = $userSkill->getUser()->getId();

        // Update du User
        $profil = $this->userRepository->find($userIdToRedirect);
        $profil->setModifiedAt(new \DateTime());

        $this->em->remove($userSkill);
        $this->em->flush();
        
        return $this->redirect($this->generateUrl('profil', array('id' => $userIdToRedirect)));
    }
}
