<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Status;
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

        // Plus de pertinence pour les expériences
        $faker->addProvider(new \Bezhanov\Faker\Provider\Educator($faker));

        // Function experience
        function addXp($faker, $user, $manager) {

            // Ajout d'expériences Random
            $nbExp = rand(1, 8);
            for ($e = 1; $e <= $nbExp; $e++) {
                $experience = new Experience();

                $experience->setName($faker->university())
                    ->setDescription($faker->course())
                    ->setUser($user)
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

        $admin->setFirstname("'Nicolas")
        ->setLastname('Marcaud')
        ->setEmail("admin@gmail.com")
        ->setPassword($hash)
        ->setRoles(['ROLE_ADMIN'])
        ->setPhone($faker->phoneNumber())
        ->setAdresse($faker->address())
        ->setStatus($status)
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
                ->setAdresse($faker->address())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setCreatedAt($faker->dateTime());

            // Ajout d'expériences Random
            addXp($faker, $user, $manager);
            
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
                ->setAdresse($faker->address())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setCreatedAt($faker->dateTime());

            // Ajout d'expériences Random
            addXp($faker, $user, $manager);
            
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
                ->setAdresse($faker->address())
                ->setPhone($faker->phoneNumber())
                ->setStatus($status)
                ->setCreatedAt($faker->dateTime());

            // Ajout d'expériences Random
            addXp($faker, $user, $manager);
            
            $manager->persist($user);
        }



        // Compétences
        $skill = new Skill;
        $skill->setName('Php')->setCreatedAt($faker->dateTime());
        $manager->persist($skill);
        $skill = new Skill;
        $skill->setName('Ruby')->setCreatedAt($faker->dateTime());
        $manager->persist($skill);
        $skill = new Skill;
        $skill->setName('Javascript')->setCreatedAt($faker->dateTime());
        $manager->persist($skill);
        $skill = new Skill;
        $skill->setName('HTML')->setCreatedAt($faker->dateTime());
        $manager->persist($skill);
        $skill = new Skill;
        $skill->setName('CSS')->setCreatedAt($faker->dateTime());
        $manager->persist($skill);
        $skill = new Skill;
        $skill->setName('Bootstrap')->setCreatedAt($faker->dateTime());
        $manager->persist($skill);



        $manager->flush();
    }

}
