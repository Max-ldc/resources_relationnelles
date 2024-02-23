<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserData::class);
    }

    public function isEmailHashExist(string $emailHash): bool
    {
        $existingEmail = $this->findOneBy(['emailHash' => $emailHash]);

        return null !== $existingEmail;
    }
}
