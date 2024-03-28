<?php

namespace App\Domain\User;

use App\DTO\CreateUser;
use App\Entity\User;
use App\Entity\UserData;
use App\Repository\UserDataRepository;
use App\Repository\UserRepository;
use App\Security\DatabaseEncryption;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

readonly class UserCreationOrUpdate
{
    public function __construct(
        private DatabaseEncryption $encryptionService,
        private UserDataRepository $userDataRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function createUser(CreateUser $data, string $emailHash): User
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

    public function addUserPrivilege(User $user, string $role): User
    {
        $user->setRole($role);

        return $user;
    }

    public function isUserDuplicate(string $username, string $emailHash): void
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
