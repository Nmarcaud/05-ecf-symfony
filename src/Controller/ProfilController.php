<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\UserSkill;
use App\Entity\Experience;
use App\Form\UserInfoType;
use App\Form\UserSkillType;
use App\Form\ExperienceType;
use App\Form\AddDocumentType;
use App\Repository\UserRepository;
use App\Repository\SkillRepository;
use App\Repository\CategoryRepository;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfilController extends AbstractController
{

    protected $userRepository;
    protected $categoryRepository;
    protected $experienceRepository;
    protected $skillRepository;
    protected $em;
    protected $slugger;

    public function __construct(UserRepository $userRepository, CategoryRepository $categoryRepository, ExperienceRepository $experienceRepository, SkillRepository $skillRepository, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
        $this->experienceRepository = $experienceRepository;
        $this->skillRepository = $skillRepository;
        $this->em = $em;
        $this->slugger = $slugger;
    }

    
    /**
     * @Route("{id}/profil", name="profil")
     */
    public function index($id, Request $request): Response
    {

        // Candidat ou Collaborateur
        $profil = $this->userRepository->find($id);

        // -----------------------------------//
        // AFFICHAGE DES CATEGORIES NON VIDES //
        // -----------------------------------//

            // Liste globale des catégories 
            $categories = $this->categoryRepository->findAll();
            // Liste globale des skills
            $skills = $this->skillRepository->findAll();

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
        $experiences = $this->experienceRepository->findBy(['user' => $id]);


        // --------------------------------------------//
        // FORMULAIRE DE MODIFICATION DES INFOS PERSOS //
        // --------------------------------------------//

        $form = $this->createForm(UserInfoType::class, $profil);        // 1 Création du formulaire,AVEC l'élément à modifier
        $formProfilInfoView = $form->createView();                      // 2 Création de la vue

        $form->handleRequest($request);                                 // 3 Inspecte la request ( si form soumis )
        if ($form->isSubmitted()) {                                     // 4 Si, est soumis

            $profil->setModifiedAt(new \DateTime());                      // 5 Datetime de modification
            $this->em->flush();                                         // 6 Pas besoin de persist car déjà en base
            
            return $this->redirectToRoute('profil', ["id" => $id], Response::HTTP_SEE_OTHER);
        }


        // -----------------------------//
        // FORMULAIRE D'AJOUT DE SKILLS //
        // -----------------------------//
        $userSkill = new UserSkill;

        $form = $this->createForm(UserSkillType::class, $userSkill);
        $formAddSkillView = $form->createView();

        $form->handleRequest($request); 
        if ($form->isSubmitted()) {

            $userSkill->setUser($profil);
            $userSkill->setCreatedAt(new \DateTime());
            $this->em->persist($userSkill);
            $this->em->flush();

            return $this->redirectToRoute('profil', ["id" => $id], Response::HTTP_SEE_OTHER);
        }

        // --------------------------------//
        // FORMULAIRE D'AJOUT D'EXPERIENCE //
        // --------------------------------//
        $experience = new Experience;

        $form = $this->createForm(ExperienceType::class, $experience);
        $formAddExperrienceView = $form->createView();

        $form->handleRequest($request); 
        if ($form->isSubmitted()) {

            $experience->setUser($profil);
            $experience->setCreatedAt(new \DateTime());
            $this->em->persist($experience);
            $this->em->flush();

            return $this->redirectToRoute('profil', ["id" => $id], Response::HTTP_SEE_OTHER);
        }


        // ---------------------------------//
        // FORMULAIRE D'AJOUT D'UN DOCUMENT //
        // ---------------------------------//
        $document = new Document;

        $form = $this->createForm(AddDocumentType::class, $document);
        $formAddDocumentView = $form->createView();

        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $documentFile */
            $documentFile = $form->get('file')->getData();

            // Renommage
            if ($form->get('name')->getData() != null) {
                $originalFilename = $form->get('name')->getData();
            } else {
                $originalFilename = pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME);
            }
           
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$documentFile->guessExtension();

            // Move the file to the directory where documents are stored
            try {
                $documentFile->move(
                    $this->getParameter('document_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                dd($e);
            }        
            $document->setFile($newFilename);

            $document->setUser($profil);
            $document->setCreatedAt(new \DateTime());
            $this->em->persist($document);
            $this->em->flush();

            return $this->redirectToRoute('profil', ["id" => $id], Response::HTTP_SEE_OTHER);
        }

    
        return $this->render('profil/index.html.twig', [
            'profil' => $profil,
            'categories' => $categoriesView,
            'experiences' => $experiences,
            'formProfilInfoView' => $formProfilInfoView,
            'formAddSkillView' => $formAddSkillView,
            'formAddExperrienceView' => $formAddExperrienceView,
            'formAddDocumentView' => $formAddDocumentView
        ]);
    }
}
