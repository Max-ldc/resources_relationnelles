<?php

declare(strict_types=1);

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\User\UserCreationOrUpdate;
use App\DTO\CreateUser;
use App\Repository\UserRepository;

readonly class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserCreationOrUpdate $userCreationOrUpdate,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof CreateUser) {
            $emailHash = hash('sha256', $data->getEmail());
            $username = $data->getUserName();

            $this->userCreationOrUpdate->isUserDuplicate($username, $emailHash);

            $user = $this->userCreationOrUpdate->createUser($data, $emailHash);

            // ManyToOne will persist the item
            $this->userRepository->save($user);
        }
    }
}
