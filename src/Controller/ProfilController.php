<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ExperienceRepository;
use App\Repository\SkillRepository;
use App\Repository\UserRepository;
use App\Repository\UserSkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("{id}/profil", name="profil")
     */
    public function index($id, UserRepository $userRepository, CategoryRepository $categoryRepository, ExperienceRepository $experienceRepository , SkillRepository $skillRepository): Response
    {

        // Candidat ou Collaborateur
        $profil = $userRepository->find($id);

        // -----------------------------------//
        // AFFICHAGE DES CATEGORIES NON VIDES //
        // -----------------------------------//

            // Liste globale des catégories 
            $categories = $categoryRepository->findAll();
            // Liste globale des skills
            $skills = $skillRepository->findAll();

            // Liste des ids des compétences du user
            $skillIds = array();
            foreach ($profil->getUserSkills() as $skill) {
                array_push($skillIds, $skill->getSkill()->getId());
            }

            // Liste des catégories "concernées" par ce user
            $categoriesView = array();

            foreach($categories as $categorie) {
                foreach($skills as $skill) {

                    // Si skill appartient à cette catégorie ( Je compare les noms )
                    if ($skill->getCategory()->getName() == $categorie->getName()) {
                        
                        // Si skill id est dans l'array du user, alors, add catégorie
                        if (in_array($skill->getId(), $skillIds)){
                            if(!in_array($categorie, $categoriesView)) {
                                array_push($categoriesView, $categorie);
                            }
                        }  
                    }
                }
            }
        
        // --------------------------//
        // AFFICHAGE DES EXPERIENCES //
        // --------------------------//
        $experiences = $experienceRepository->findBy(['user' => $id]);


        return $this->render('profil/index.html.twig', [
            'profil' => $profil,
            'categories' => $categoriesView,
            'experiences' => $experiences
        ]);
    }
}
