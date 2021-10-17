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
        $status = new Status;
        $status->setName("'Administrateur");
        $manager->persist($status);
        $status = new Status;
        $status->setName("'Collaborateur");
        $manager->persist($status);
        $status = new Status;
        $status->setName("'Commercial");
        $manager->persist($status);
        $status = new Status;
        $status->setName("'Candidat");
        $manager->persist($status);


        // Init Faker to FR
        $faker = Factory::create('fr_FR');

        // Admin
        $admin = new User;
        
        $hash = $this->encoder->hashPassword($admin, "password");

        $admin->setFirstname("'Nicolas")
        ->setLastname('Marcaud')
        ->setEmail("nmarcau@gmail.com")
        ->setPassword($hash)
        ->setRoles(['ROLE_ADMIN'])
        ->setPhone($faker->phoneNumber())
        ->setAdresse($faker->address())
        ->setCreatedAt($faker->dateTime());

        $manager->persist($admin);

        // Utilisateurs
        for($u = 0; $u < 10; $u++) {

            $user = new User();

            $hash = $this->encoder->hashPassword($user, "password");

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("user_$u@gmail.com")
                ->setPassword($hash)
                ->setAdresse($faker->address())
                ->setPhone($faker->phoneNumber())
                ->setCreatedAt($faker->dateTime());
            
            $manager->persist($user);
        }
        $manager->flush();
    }
}
