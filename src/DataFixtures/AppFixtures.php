<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        // Ajouter un Admin
        $admin = new User();

        $admin->setEmail('admin@admin.fr');
        $admin->setUsername($faker->lastName());
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'Azerty1+'));

        $manager->persist($admin);
        $this->addReference('admin', $admin);

        // Ajouter 3 taches pour l'Admin
        for($i=0; $i<3; $i++) {
            $task = new Task();

            $task->setTitle($faker->realtext(10, 2));
            $task->setContent($faker->realtext(70, 2));
            $task->setCreatedAt($faker->dateTimeBetween('-1 month', 'now'));
            $task->setAuthor($admin);
            $manager->persist($task);
        }
        
        // Ajouter (05) Users
        for($i=0; $i<5; $i++) {
            $user = new User();

            $user->setEmail($faker->email());
            $user->setUsername($faker->lastName());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($admin, 'Azerty1+'));
            $manager->persist($user);

        // Ajouter 3 taches pour chaque User
        for($k=0; $k<3; $k++) {
            $task = new Task();

            $task->setTitle($faker->realtext(10, 2));
            $task->setContent($faker->realtext(70, 2));
            $task->setCreatedAt($faker->dateTimeBetween('-1 month', 'now'));
            $task->setAuthor($user);
            $manager->persist($task);
        }

        }

        $manager->flush();
    }
}
