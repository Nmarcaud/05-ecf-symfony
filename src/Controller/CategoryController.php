<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @Route("/category")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class CategoryController extends AbstractController
{
    
    /**
     * @Route("/", name="category")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        // Liste des skills
        $listCategories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            "categories" => $listCategories
        ]);
    }


    /**
     * @Route("/create", name="category_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $category->setCreatedAt(new \DateTime());

            $em->persist($category);
            $em->flush();
            
            return $this->redirectToRoute('category', [], Response::HTTP_SEE_OTHER);
        }
 
        return $this->render('category/create.html.twig', [
            'formView' => $formView
        ]);
    }


    /**
     * @Route("/{id}/edit", name="category_edit")
     */
    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em): Response
    {
        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $category->setModifiedAt(new \DateTimeImmutable());
            $em->flush();
            
            return $this->redirectToRoute('category', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'formView' => $formView,
            'category' => $category
        ]);
    }


    /**
     * @Route("/{id}/delete", name="category_delete")
     */
    public function delete($id, CategoryRepository $categoryRepository, EntityManagerInterface $em): Response
    {
        $category = $categoryRepository->find($id);
        $em->remove($category);
        $em->flush();
        
        return $this->redirectToRoute('category', [], Response::HTTP_SEE_OTHER);
    }
}
