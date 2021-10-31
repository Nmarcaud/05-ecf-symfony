<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/entreprises")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class EntreprisesController extends AbstractController
{
    /**
     * @Route("/", name="entreprises")
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



    /**
     * @Route("/create", name="entreprises_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $entreprise = new Entreprise;
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $entreprise->setCreatedAt(new \DateTime());

            $em->persist($entreprise);
            $em->flush();
            
            return $this->redirectToRoute('entreprises', [], Response::HTTP_SEE_OTHER);
        }
 
        return $this->render('entreprises/create.html.twig', [
            'formView' => $formView
        ]);
    }


    /**
     * @Route("/{id}/edit", name="entreprises_edit")
     */
    public function edit($id, EntrepriseRepository $entrepriseRepository, Request $request, EntityManagerInterface $em): Response
    {
        $entreprise = $entrepriseRepository->find($id);

        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $entreprise->setModifiedAt(new \DateTime());
            $em->flush();
            
            return $this->redirectToRoute('entreprises', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprises/edit.html.twig', [
            'formView' => $formView,
            'entreprise' => $entreprise
        ]);
    }


    /**
     * @Route("/{id}/delete", name="entreprises_delete")
     */
    public function delete($id, EntrepriseRepository $entrepriseRepository, EntityManagerInterface $em): Response
    {
        $entreprise = $entrepriseRepository->find($id);
        $em->remove($entreprise);
        $em->flush();
        
        return $this->redirectToRoute('entreprises', [], Response::HTTP_SEE_OTHER);
    }
}
