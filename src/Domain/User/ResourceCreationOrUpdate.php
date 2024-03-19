<?php

namespace App\Domain\User;

use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Exception\ItemNotFoundException;
use ApiPlatform\Exception\RuntimeException;
use App\Entity\RelationType;
use App\Entity\Resource;
use App\Entity\ResourceMetadata;
use App\Entity\UserData;
use App\Storage\FileSystemAdaptor;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ResourceCreationOrUpdate
{
    public function __construct(
        private readonly FileSystemAdaptor $fileSystemAdaptor,
        private readonly IriConverterInterface $iriConverter,
    ) {
    }

    public function createResource(string $originalFileName, array $jsonData, UserData $userData): Resource
    {
        $resource = new Resource();
        $resource
            ->setFileName($originalFileName)
            ->setSharedStatus($jsonData['sharedStatus'])
            ->setCategory($jsonData['category'])
            ->setType($jsonData['type'])
            ->setUserData($userData);

        foreach ($jsonData['relationTypes'] as $relationTypeIri) {
            try {
                /** @var RelationType $relationType */
                $relationType = $this->iriConverter->getResourceFromIri($relationTypeIri);
                $resource->addResourceRelationType($relationType);
            } catch (ItemNotFoundException|\InvalidArgumentException) {
                throw new BadRequestHttpException('RelationType not found');
            }
        }

        return $resource;
    }

    public function createResourceMetadata(array $jsonData): ResourceMetadata
    {
        $resourceMeta = new ResourceMetadata();
        $resourceMeta
            ->setAuthor($jsonData['author'])
            ->setTitle($jsonData['title']);

        return $resourceMeta;
    }

    public function saveFile(string $filePath, string $originalFileName): void
    {
        try {
            $fileContent = file_get_contents($filePath);
            $this->fileSystemAdaptor->addFile($originalFileName, $fileContent);
        } catch (\Exception $exception) {
            throw new RuntimeException('Unable to save file');
        }
    }
}
