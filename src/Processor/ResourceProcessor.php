<?php

declare(strict_types=1);

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\CreateResource;
use App\Entity\Resource;
use App\Entity\ResourceMetadata;
use App\Repository\ResourceRepository;
use App\Repository\UserRepository;
use App\Storage\FileSystemAdaptor;

readonly class ResourceProcessor implements ProcessorInterface
{
    public function __construct(
        private ResourceRepository $resourceRepository,
        private UserRepository $userRepository,
        private FileSystemAdaptor $fileSystem,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        //        var_dump($data);
        //        echo "Coucou je suis le processor";
        //        var_dump($data);
        //        if ($data instanceof CreateResource) {
        //            echo "coucou je suis instance de CreateResource !";
        //            $resource = $this->createResource($data);
        //
        //            $this->resourceRepository->save($resource);
        //        }
    }

    //    private function createResource(CreateResource $data): Resource
    //    {
    //        // User mock, to be deleted when we will be capable of retrieving user ID's at the resource creation
    // //        $user = $this->userRepository->find(1);
    // //        $userData = $user->getUserData();
    // //
    // //        $fileName = $data->getFile()->getFilename();
    // //        $fileContent = $data->getFile()->getContent();
    // //
    // //        $metadata = (new ResourceMetadata())
    // //            ->setTitle($data->getTitle())
    // //            ->setDuration($data->getDuration())
    // //            ->setAuthor($data->getAuthor())
    // //            ->setFormat($data->getFormat())
    // //            ->setAlbum($data->getAlbum())
    // //            ->setGenre($data->getGenre());
    // //
    // //        $resource = (new Resource())
    // //            ->setFileName($fileName)
    // //            ->setSharedStatus($data->getSharedStatus())
    // //            ->setCategory($data->getCategory())
    // //            ->setType($data->getType())
    // //            ->setUserData($userData)
    // //            ->setResourceMetadata($metadata);
    // //
    // //
    // //        $this->fileSystem->addFile($data->getFile()->getFilename(), $fileContent);
    //
    //        return $resource;
    //    }
}
