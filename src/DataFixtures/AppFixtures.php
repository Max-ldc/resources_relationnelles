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

        $usernames = ["Pedro", "Maria"];
        $emailsEncrypted = ["+54tnk8ef6F4oJ4feVBpSJa4hnd38lVlI/FW5JBT6/0UfsC5jwIGZoHpRpEoxVb+", "1+Luw8//VIa9d8dwqWuZPeE+8hct46534ZIO0HGzkMkOeDwTelUc4GvqC3/5fPJd"];

        foreach ($usernames as $index => $username) {
            $user = new User();
            $user->setUsername($username)
                ->setAccountEnabled(true);

            $emailEncrypted = $emailsEncrypted[$index];

            $email = strtolower($username) . "@example.com";

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
