<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Status;
use App\Entity\Category;
use App\Entity\Entreprise;
use App\Entity\Experience;
use App\Entity\UserSkill;
use App\Repository\SkillRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    // Ajout d'un encoder de password
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);


        // Init Faker to FR
        $faker = Factory::create('fr_FR');
        // Plus de pertinence pour les expériences et entreprises
        $faker->addProvider(new \Bezhanov\Faker\Provider\Educator($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));


        // Autres Type de Users - On sait jamais !
        $status = new Status;
        $status->setName("Autre");
        $manager->persist($status);


        // Liste Skills pour UserSkills
        $skills = array();

        // Compétences & Catégories
        $category = new Category;
        $category->setName('Front')->setCreatedAt($faker->dateTime());
        $manager->persist($category);

            $skill = new Skill;
            $skill->setName('Javascript')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('HTML')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('CSS')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Bootstrap')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
        
        // Compétences & Catégories
        $category = new Category;
        $category->setName('Back')->setCreatedAt($faker->dateTime());
        $manager->persist($category);

            $skill = new Skill;
            $skill->setName('Php')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Ruby')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Python')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('NodeJS')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            
        // Compétences & Catégories
        $category = new Category;
        $category->setName('CMS')->setCreatedAt($faker->dateTime());
        $manager->persist($category);

            $skill = new Skill;
            $skill->setName('Wordpress')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Cardstack')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Prestashop')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Shopify')->setCreatedAt($faker->dateTime())->setCategory($category);
            array_push($skills, $skill);
            $manager->persist($skill);



        // Entreprises
        /* Je génère d'abord une liste d'entreprises pour ensuite en choisir une au hasard et lui assigner une expérience */
        $listEntreprises = array();
        for ($e = 0; $e < 15; $e++) {
            
            $entreprise = new Entreprise;
            $entreprise->setName($faker->company())
                ->setCreatedAt($faker->dateTime());
            
            // Ajout à la liste pour réutilisation dans " experience "
            array_push($listEntreprises, $entreprise);
            $manager->persist($entreprise);
        }

        // Function experience
        /* Je génère un nbre random d'expériences / La focntion me permet de la réultilser pur chaque type de status */
        function addXp($faker, $user, $manager, $listEntreprises) {

            // Ajout d'expériences Random
            $nbExp = rand(1, 8);
            for ($e = 1; $e <= $nbExp; $e++) {

                $experience = new Experience();

                $experience->setEntreprise($listEntreprises[rand(0, count($listEntreprises)-1)])
                    ->setTitle($faker->jobTitle())
                    ->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                    ->setUser($user)
                    ->setStartDate($faker->dateTime())
                    ->setEndDate($faker->dateTime())
                    ->setCreatedAt($faker->dateTime());
                
                $manager->persist($experience);
            }
        }




        // Liste Users pour UserSkills
        $users = array();

        // Admin
        // Création Status Administrateur
        $status = new Status;
        $status->setName("Administrateur");
        $manager->persist($status);

        $admin = new User;
        
        // Mot de passe encodé
        $hash = $this->encoder->hashPassword($admin, "password");

        $admin->setFirstname("Nicolas")
        ->setLastname('Marcaud')
        ->setEmail("admin@gmail.com")
        ->setPassword($hash)
        ->setJobTitle($faker->jobTitle())
        ->setRoles(['ROLE_ADMIN', 'ROLE_COLLABORATEUR', 'ROLE_COMMERCIAL', 'ROLE_CANDIDAT'])
        ->setPhone($faker->phoneNumber())
        ->setAdresse($faker->streetAddress())
        ->setZipCode($faker->postcode())
        ->setCity($faker->city())
        ->setStatus($status)
        ->setPictureUrl($faker->imageUrl(300,300, true))
        ->setCreatedAt($faker->dateTime())
        ->setApsideBirthday($faker->dateTime())
        ->setDisponibility(rand(0,1));

        array_push($users, $admin);
        $manager->persist($admin);

        // Ajout d'expériences Random
        addXp($faker, $admin, $manager, $listEntreprises);

        

        // Utilisateurs
        // Création Status Collaborateur
        $status = new Status;
        $status->setName("Collaborateur");
        $manager->persist($status);

        for($u = 0; $u < 22; $u++) {

            $user = new User();

            // Mot de passe encodé
            $hash = $this->encoder->hashPassword($user, "password");

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("collaborateur_$u@gmail.com")
                ->setPassword($hash)
                ->setJobTitle($faker->jobTitle())
                ->setRoles(['ROLE_COLLABORATEUR'])
                ->setAdresse($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setPictureUrl($faker->imageUrl(300,300, true))
                ->setCreatedAt($faker->dateTime())
                ->setApsideBirthday($faker->dateTime())
                ->setDisponibility(rand(0,1));

            // Ajout d'expériences Random
            addXp($faker, $user, $manager, $listEntreprises);

            array_push($users, $user);
            $manager->persist($user);
        }
        // Nouveau Collaborateur
        for ($u = 0; $u < 2; $u++) { 
            $user = new User();
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("collaborateur_new_$u@gmail.com")
                ->setPassword($hash)
                ->setJobTitle($faker->jobTitle())
                ->setRoles(['ROLE_COLLABORATEUR'])
                ->setAdresse($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setPictureUrl($faker->imageUrl(300, 300, true))
                ->setCreatedAt(new \DateTime())
                ->setApsideBirthday($faker->dateTime())
                ->setDisponibility(rand(0, 1));
            addXp($faker, $user, $manager, $listEntreprises);
            array_push($users, $user);
            $manager->persist($user);
        }
        // Collaborateurs Modifiés
        for ($u = 0; $u < 2; $u++) { 
            $user = new User();
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("collaborateur_modify_$u@gmail.com")
                ->setPassword($hash)
                ->setJobTitle($faker->jobTitle())
                ->setRoles(['ROLE_COLLABORATEUR'])
                ->setAdresse($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setPictureUrl($faker->imageUrl(300, 300, true))
                ->setModifiedAt(new \DateTime())
                ->setCreatedAt($faker->dateTime())
                ->setApsideBirthday($faker->dateTime())
                ->setDisponibility(rand(0, 1));
            addXp($faker, $user, $manager, $listEntreprises);
            array_push($users, $user);
            $manager->persist($user);
        }


        // Commerciaux
        // Création Status Commercial
        $status = new Status;
        $status->setName("Commercial");
        $manager->persist($status);

        for($u = 0; $u < 15; $u++) {

            $user = new User();

            // Mot de passe encodé
            $hash = $this->encoder->hashPassword($user, "password");

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("commercial_$u@gmail.com")
                ->setPassword($hash)
                ->setJobTitle($faker->jobTitle())
                ->setRoles(['ROLE_COMMERCIAL'])
                ->setAdresse($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setPictureUrl($faker->imageUrl(300,300, true))
                ->setCreatedAt($faker->dateTime())
                ->setApsideBirthday($faker->dateTime())
                ->setDisponibility(rand(0,1));

            // Ajout d'expériences Random
            addXp($faker, $user, $manager, $listEntreprises);

            array_push($users, $user);
            $manager->persist($user);
        }

       
        // Candidats
        // Création Status Candidat
        $status = new Status;
        $status->setName("Candidat");
        $manager->persist($status);

        for($u = 0; $u < 44; $u++) {

            $user = new User();

            // Mot de passe encodé
            $hash = $this->encoder->hashPassword($user, "password");

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("candidat_$u@gmail.com")
                ->setPassword($hash)
                ->setJobTitle($faker->jobTitle())
                ->setRoles(['ROLE_CANDIDAT'])
                ->setAdresse($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setPictureUrl($faker->imageUrl(300,300, true))
                ->setCreatedAt($faker->dateTime())
                ->setApsideBirthday(null)
                ->setDisponibility(rand(0,1));

            // Ajout d'expériences Random
            addXp($faker, $user, $manager, $listEntreprises);
            array_push($users, $user);
            $manager->persist($user);
        }

        for ($u = 0; $u < 2; $u++) {
            // Nouveau Candidat
            $user = new User();
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("candidat_new_$u@gmail.com")
                ->setPassword($hash)
                ->setJobTitle($faker->jobTitle())
                ->setRoles(['ROLE_CANDIDAT'])
                ->setAdresse($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setPictureUrl($faker->imageUrl(300, 300, true))
                ->setCreatedAt(new \DateTime())
                ->setApsideBirthday($faker->dateTime())
                ->setDisponibility(rand(0, 1));
    
            // Ajout d'expériences Random
            addXp($faker, $user, $manager, $listEntreprises);
            array_push($users, $user);
            $manager->persist($user);
        }



        // Ajout User_Skill
        
        foreach ($users as $user) {

            // réinitialise la liste
            $listSkills = $skills;

            $nbSkills = rand(1, 6);
            for ($e = 1; $e <= $nbSkills; $e++) {

                $countSkills = count($listSkills)-1;
                $randSkill = rand(0, $countSkills);
                // Attribut un skill aléatoire
                $skill = $listSkills[$randSkill];
                // retire le skill de la liste er ré-index la liste
                array_splice($listSkills, $randSkill, 1); 

                $userSkill = new UserSkill;
                $userSkill->setUser($user)->setSkill($skill)->setCreatedAt($faker->dateTime())->setLevel(rand(1,5))->setApprecied(rand(0,1));
                $manager->persist($userSkill);
            }
        }
        $manager->flush();


    }

}
