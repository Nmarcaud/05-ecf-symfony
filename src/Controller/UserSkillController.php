<?php

namespace App\Controller;

use App\Repository\UserSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/user/skill")
 */
class UserSkillController extends AbstractController
{
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
     * @Route("/{user_skill_id}/delete", name="user_skills_delete")
     */
    public function delete($user_skill_id, UserSkillRepository $userSkillRepository, EntityManagerInterface $em): Response
    {
        $userSkill = $userSkillRepository->findOneBy([
            'id' =>$user_skill_id
        ]);
        
        // Je chope le user id avent supression pour redirect
        $userIdToRedirect = $userSkill->getUser()->getId();

        $em->remove($userSkill);
        $em->flush();
        
        return $this->redirect($this->generateUrl('profil', array('id' => $userIdToRedirect)));
    }
}
