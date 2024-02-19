<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $usernames = ['Pedro', 'Maria', 'UserForDeleteTest'];
        $emailsEncrypted = [
            '9RiA1iLwrzFwGM/E45V8swdw0dQCvPKGs9/5OQhgQGdZ8kBKm4Zlrw7NIDZf3HZi',
            'mZhTA1q/KhjLltgxh9UXn3hRNkhm1nMSwaohh5Ix5WWXSJt4zv1hjtgX+UUm9gPK',
            'FnFbcypAOBl0+s/IVr8KOlFL83Re6dMnOBOVMNAlBcTjWv6xtjNJkDRiM6Djd8wy',
        ];

        foreach ($usernames as $index => $username) {
            $user = new User();
            $user->setUsername($username)
                ->setAccountEnabled(true);

            $emailEncrypted = $emailsEncrypted[$index];

            $email = strtolower($username).'@example.com';

            $userData = new UserData();
            $userData->setUser($user)
                ->setEmailEncrypted($emailEncrypted)
                ->setEmailHash(hash('sha256', $email));

            $user->setUserData($userData);

            $manager->persist($user);
            $manager->persist($userData);
        }

        $manager->flush();
    }
}
