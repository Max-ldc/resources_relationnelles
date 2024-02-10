<?php

declare(strict_types=1);

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\UserApi;
use App\Entity\User;
use App\Entity\UserData;
use App\Repository\UserDataRepository;
use App\Repository\UserRepository;
use App\Security\DatabaseEncryption;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

readonly class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private DatabaseEncryption $encryptionService,
        private EntityManagerInterface $entityManager,
        private UserDataRepository $userDataRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof UserApi) {
            $emailHash = hash('sha256', $data->getEmail());
            $username = $data->getUserName();

            $this->isUserDuplicate($username, $emailHash);

            $user = $this->createUser($data, $emailHash);

            $this->entityManager->persist($user);
            $this->entityManager->persist($user->getUserData());
            $this->entityManager->flush();
        }
    }

    private function createUser(UserApi $data, string $emailHash): User
    {
        $emailEncrypt = $this->encryptionService->encrypt($data->getEmail());

        $user = (new User())
            ->setUserName($data->getUserName());

        $userData = (new UserData())
            ->setUser($user)
            ->setEmailEncrypted($emailEncrypt)
            ->setEmailHash($emailHash);

        $user->setUserData($userData);

        return $user;
    }

    private function isUserDuplicate(string $username, string $emailHash): void
    {
        $existingEmail = $this->userDataRepository->isEmailHashExist($emailHash);
        $existingUsername = $this->userRepository->isUsernameTaken($username);

        if ($existingEmail) {
            throw new BadRequestHttpException('Cet email est déjà utilisé.');
        }

        if ($existingUsername) {
            throw new BadRequestHttpException("Le nom d'utilisateur est déjà utilisé.");
        }
    }
}
