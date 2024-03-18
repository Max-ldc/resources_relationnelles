<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Exception\ItemNotFoundException;
use App\Entity\RelationType;
use App\Entity\Resource;
use App\Entity\ResourceMetadata;
use App\Entity\UserData;
use App\Repository\ResourceRepository;
use App\Storage\FileSystemAdaptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController] // use a custom Controller because ApiPlatform cannot natively handle multipart
class CreateResourceController extends AbstractController
{
    public function __construct(
        private readonly FileSystemAdaptor $fileSystemAdaptor,
        private readonly IriConverterInterface $iriConverter,
        private readonly ResourceRepository $resourceRepository,
    ) {
    }

    // TO DO : refacto
    public function __invoke(Request $request): Response
    {
        // Retrieve the file :
        $uploadedFile = $request->files->get('importFile');
        if (!$uploadedFile) {
            return $this->json(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        $originalFileName = $uploadedFile->getClientOriginalName();

        // Save file on MinIO container :
        try {
            $fileContent = file_get_contents($uploadedFile->getRealPath());
            $this->fileSystemAdaptor->addFile($originalFileName, $fileContent);
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Extraction et decode du json
        $jsonData = json_decode($request->get('json'), true, 512, JSON_THROW_ON_ERROR);

        // TO DO : implement authentication to retrieve the current user. Temporary solution :
        try {
            /** @var UserData $userData */
            $userData = $this->iriConverter->getResourceFromIri($jsonData['userData']);
        } catch (ItemNotFoundException|\InvalidArgumentException) {
            return new JsonResponse(['error' => 'UserData not found'], Response::HTTP_BAD_REQUEST);
        }

        // Create the Resource and its metadatas
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
                return new JsonResponse(['error' => 'RelationType not found'], Response::HTTP_BAD_REQUEST);
            }
        }

        $resourceMeta = new ResourceMetadata();
        $resourceMeta
            ->setAuthor($jsonData['author'])
            ->setTitle($jsonData['title']);

        $resource->setResourceMetadata($resourceMeta);

        // Save the Resource and its metadatas
        $this->resourceRepository->save($resource);

        return $this->json(['status' => 'File uploaded successfully', 'fileName' => $originalFileName]);
    }
}
