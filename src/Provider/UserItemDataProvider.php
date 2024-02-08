<?php

namespace App\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Repository\UserRepository;

final class UserItemDataProvider implements ProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|User|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->userRepository->findAll();
        }

        $id = $uriVariables['id'] ?? null;
        return $this->userRepository->find($id);
    }
}
