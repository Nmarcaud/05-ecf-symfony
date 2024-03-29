<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @Route("/search")
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMMERCIAL')")
 */
class SearchController extends AbstractController
{   
    /**
     * @Route("/", name="search")
     */
    public function searchBar(UserRepository $userRepository, Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $searchFormView = $form->createView();

        $form->handleRequest($request);
        // if ($form->isSubmitted()) {

        //     $searchResults = array();

        //     // Je cherche dans les noms
        //     array_push($searchResults, $userRepository->find(['fistname']));
        //     dd($searchResults);
        //     // Je cerche dans les prénoms
      
        //     return $this->redirect($this->generateUrl('profil'));
        // }

        return $this->render('search.html.twig', [
            'test' => 'coucou',
            'searchFormView' => $searchFormView
        ]);
    }
}
