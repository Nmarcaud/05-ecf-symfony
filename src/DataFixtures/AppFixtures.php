<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Status;
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

    public function load( ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // Admin
       
        
        

        $manager->flush();

        // Init Faker to FR
        $faker = Factory::create('fr_FR');


        
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
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}
