<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\User as UserDto;
use App\Entity\User;
use App\Security\DatabaseEncryption;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

readonly class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private DatabaseEncryption $encryptionService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof UserDto) {
            $user = new User();
            $user->setUserName($data->getUserName());
            $user->setEmail($this->encryptionService->encrypt($data->getEmail()));
            $user->setFirstName($this->encryptionService->encrypt($data->getFirstName()));
            $user->setLastName($this->encryptionService->encrypt($data->getLastName()));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}
