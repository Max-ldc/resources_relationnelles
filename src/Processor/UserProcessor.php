<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\User as UserDto;
use App\Entity\User;
use App\Entity\UserData;
use App\Security\DatabaseEncryption;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Uid\Uuid;

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

            $emailHash = hash('sha256', $data->getEmail());

            $user = (new User())
                ->setUserName($data->getUserName());

            $emailEncrypted = $this->encryptionService->encrypt($data->getEmail());

            $userData = (new UserData())
                ->setUser($user)
                ->setEmailEncrypted($emailEncrypted)
                ->setEmailHash($emailHash);

            $user->setUserData($userData);

            $this->entityManager->persist($user);
            $this->entityManager->persist($userData);
            $this->entityManager->flush();
        }
    }
}
