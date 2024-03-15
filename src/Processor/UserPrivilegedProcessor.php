<?php

declare(strict_types=1);

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\User\UserCreationOrUpdate;
use App\DTO\CreateUser;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

readonly class UserPrivilegedProcessor implements ProcessorInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserCreationOrUpdate $userCreationOrUpdate,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data->getRole() === null) {
            throw new BadRequestHttpException('Providing a role is mandatory');
        }

        if ($data instanceof CreateUser) {
            $emailHash = hash('sha256', $data->getEmail());
            $username = $data->getUserName();

            $this->userCreationOrUpdate->isUserDuplicate($username, $emailHash);

            $user = $this->userCreationOrUpdate->createUser($data, $emailHash);
            $user = $this->userCreationOrUpdate->addUserPrivilege($user, $data->getRole());

            // ManyToOne will persist the item
            $this->userRepository->save($user);
        }
    }
}
