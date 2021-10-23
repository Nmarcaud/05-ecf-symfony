<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Status;
use App\Entity\Category;
use App\Entity\Entreprise;
use App\Entity\Experience;
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


        // Entreprises
        /* Je génère d'abord une liste d'entreprises pour ensuite en choisir une au hasard et lui assigner une expérience */
        $listEntreprises = array();
        for ($e = 0; $e < 15; $e++) {
            
            $entreprise = new Entreprise;
            $entreprise->setName($faker->university())
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
                    ->setDescription($faker->course())
                    ->setUser($user)
                    ->setStartDate($faker->dateTime())
                    ->setEndDate($faker->dateTime())
                    ->setCreatedAt($faker->dateTime());
                
                $manager->persist($experience);
            }
        }
        


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
        ->setRoles(['ROLE_ADMIN'])
        ->setPhone($faker->phoneNumber())
        ->setAdresse($faker->streetAddress())
        ->setZipCode($faker->postcode())
        ->setCity($faker->city())
        ->setStatus($status)
        ->setPictureUrl($faker->imageUrl(500,500, true))
        ->setCreatedAt($faker->dateTime());

        $manager->persist($admin);



        // Utilisateurs
        // Création Status Collaborateur
        $status = new Status;
        $status->setName("Collaborateur");
        $manager->persist($status);

        for($u = 0; $u < 10; $u++) {

            $user = new User();

            // Mot de passe encodé
            $hash = $this->encoder->hashPassword($user, "password");

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("collaborateur_$u@gmail.com")
                ->setPassword($hash)
                ->setAdresse($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setPictureUrl($faker->imageUrl(500,500, true))
                ->setCreatedAt($faker->dateTime());

            // Ajout d'expériences Random
            addXp($faker, $user, $manager, $listEntreprises);
            
            $manager->persist($user);
        }



        // Commerciaux
        // Création Status Commercial
        $status = new Status;
        $status->setName("Commercial");
        $manager->persist($status);

        for($u = 0; $u < 10; $u++) {

            $user = new User();

            // Mot de passe encodé
            $hash = $this->encoder->hashPassword($user, "password");

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("commercial_$u@gmail.com")
                ->setPassword($hash)
                ->setAdresse($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setPictureUrl($faker->imageUrl(500,500, true))
                ->setCreatedAt($faker->dateTime());

            // Ajout d'expériences Random
            addXp($faker, $user, $manager, $listEntreprises);
            
            $manager->persist($user);
        }



        // Candidats
        // Création Status Candidat
        $status = new Status;
        $status->setName("Candidat");
        $manager->persist($status);

        for($u = 0; $u < 10; $u++) {

            $user = new User();

            // Mot de passe encodé
            $hash = $this->encoder->hashPassword($user, "password");

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("candidat_$u@gmail.com")
                ->setPassword($hash)
                ->setAdresse($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setPictureUrl($faker->imageUrl(500,500, true))
                ->setCreatedAt($faker->dateTime());

            // Ajout d'expériences Random
            addXp($faker, $user, $manager, $listEntreprises);
            
            $manager->persist($user);
        }


        // Compétences & Catégories
        $category = new Category;
        $category->setName('Front')->setCreatedAt($faker->dateTime());
        $manager->persist($category);

            $skill = new Skill;
            $skill->setName('Javascript')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('HTML')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('CSS')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Bootstrap')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
        
        // Compétences & Catégories
        $category = new Category;
        $category->setName('Back')->setCreatedAt($faker->dateTime());
        $manager->persist($category);

            $skill = new Skill;
            $skill->setName('Php')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Ruby')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Python')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('NodeJS')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            
        // Compétences & Catégories
        $category = new Category;
        $category->setName('CMS')->setCreatedAt($faker->dateTime());
        $manager->persist($category);

            $skill = new Skill;
            $skill->setName('Wordpress')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Cardstack')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Prestashop')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);
            $skill = new Skill;
            $skill->setName('Shopify')->setCreatedAt($faker->dateTime())->setCategory($category);
            $manager->persist($skill);

        $manager->flush();



        // Ajout User_Skill


    }

}
