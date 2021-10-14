<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');

        // Admin
        $admin = new User;

        $admin->setFirstname("'Nicolas")
        ->setLastname('Marcaud')
        ->setEmail("nmarcau@gmail.com")
        ->setPassword("password")
        ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        // Utilisateurs
        for($u = 0; $u < 5; $u++) {

            $user = new User();

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("user_$u@gmail.com")
                ->setPassword("password");
            
            $manager->persist($user);
        }
        $manager->flush();
    }
}
