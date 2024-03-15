<?php

declare(strict_types=1);

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\ResourceApi;
use App\Entity\Resource;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

readonly class ResourceProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof ResourceApi) {
            $resource = $this->createResource($data);

            $this->em->persist($resource);

            $this->em->flush();
        }
    }

    private function createResource(ResourceApi $data): Resource
    {
        // User mock, to be deleted when we should retrieved user ID's at the resource creation
        $user = $this->userRepository->find(1);
        $userData = $user->getUserData();

        $resource = (new Resource())
            ->setFileName($data->getFilename())
            ->setSharedStatus($data->getSharedStatus())
            ->setCategory($data->getCategory())
            ->setType($data->getType())
            ->setUserData($userData);

        return $resource;
    }
}