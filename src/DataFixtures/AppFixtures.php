<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
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

        $faker = Factory::create('fr_FR');

        // Admin
        $admin = new User;
        
        $hash = $this->encoder->hashPassword($admin, "password");

        $admin->setFirstname("'Nicolas")
        ->setLastname('Marcaud')
        ->setEmail("nmarcau@gmail.com")
        ->setPassword($hash)
        ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        // Utilisateurs
        for($u = 0; $u < 5; $u++) {

            $user = new User();

            $hash = $this->encoder->hashPassword($user, "password");

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("user_$u@gmail.com")
                ->setPassword($hash);
            
            $manager->persist($user);
        }
        $manager->flush();
    }
}
