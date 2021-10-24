<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/add/profile")
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMMERCIAL')")
 */
class AddProfileController extends AbstractController
{
    protected $em;
    protected $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="add_profile")
     */
    public function index(Request $request): Response
    {
        $profil = new User;

        $form = $this->createForm(AddProfileType::class, $profil);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profil->setCreatedAt(new \DateTime());

            // Définitions des rôles en fonction du Status
            if ($profil->getStatus() === "Commercial") {
                $profil->setRoles(['ROLE_COMMERCIAL']);
            } elseif ($profil->getStatus() === "Collaborateur") {
                $profil->setRoles(['ROLE_COLLABORATEUR']);
            } elseif ($profil->getStatus() === "Admin") {
                $profil->setRoles(['ROLE_ADMIN']);
            } elseif ($profil->getStatus() === "Candidat") {
                $profil->setRoles(['ROLE_CANDIDAT']);
            } else {
                $profil->setRoles(['ROLE_USER']);
            }
            $this->em->persist($profil);
            $this->em->flush();
        }

        return $this->render('add_profile/index.html.twig', [
            'formView' => $formView
        ]);
    }
}
