<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Exception\ItemNotFoundException;
use App\Domain\Resource\ResourceCategoryEnum;
use App\Domain\Resource\ResourceSharedStatusEnum;
use App\Domain\Resource\ResourceTypeEnum;
use App\Domain\User\ResourceCreationOrUpdate;
use App\Entity\UserData;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController] // use a custom Controller because ApiPlatform cannot natively handle multipart
class CreateResourceController extends AbstractController
{
    private const FILE_MAX_SIZE = 8_000_000;
    private const FILE_IMPORT_MIME_TYPES = ['application/pdf'];

    public function __construct(
        private readonly IriConverterInterface $iriConverter,
        private readonly ResourceCreationOrUpdate $resourceCreationOrUpdate,
        private readonly ResourceRepository $resourceRepository,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $uploadedFile = $request->files->get('importFile');
        if (!$uploadedFile) {
            return $this->json(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        // Validate file format
        $fileValidationReponse = $this->validateFile($uploadedFile);
        if ($fileValidationReponse !== null) {
            return $fileValidationReponse;
        }

        $originalFileName = $uploadedFile->getClientOriginalName();
        $filePath = $uploadedFile->getRealPath();
        $jsonData = json_decode($request->get('json'), true, 512, JSON_THROW_ON_ERROR);

        // Validate data
        $dataValidationResponse = $this->validateData($jsonData);
        if ($dataValidationResponse !== null) {
            return $dataValidationResponse;
        }

        // TO DO : implement authentication to retrieve the current user. Temporary solution :
        try {
            /** @var UserData $userData */
            $userData = $this->iriConverter->getResourceFromIri($jsonData['userData']);
        } catch (ItemNotFoundException|\InvalidArgumentException) {
            throw new BadRequestHttpException('UserData not found');
        }

        // Save file on MinIO container :
        $this->resourceCreationOrUpdate->saveFile($filePath, $originalFileName);

        // Create the Resource and its metadata
        $resource = $this->resourceCreationOrUpdate->createResource($originalFileName, $jsonData, $userData);
        $resourceMeta = $this->resourceCreationOrUpdate->createResourceMetadata($jsonData);
        $resource->setResourceMetadata($resourceMeta);

        $this->resourceRepository->save($resource);

        return $this->json(['status' => 'File uploaded successfully', 'fileName' => $originalFileName],
            Response::HTTP_CREATED);
    }

    private function validateData(array $jsonData): ?Response
    {
        // As we use a custom controller, the asserts on the DTO are not read so we do it here to avoid 500
        $constraints = new Assert\Collection([
            'fields' => [
                'title' => [
                    new Assert\NotBlank([
                    'message' => 'validation.resource.title.empty',
                ]),
            ],
                'sharedStatus' => [
                    new Assert\Choice([
                        'callback' => [ResourceSharedStatusEnum::class, 'values'],
                        'message' => 'validation.resource.sharedStatus.invalid',
                    ]),
                ],
                'category' => [
                    new Assert\Choice([
                        'callback' => [ResourceCategoryEnum::class, 'values'],
                        'message' => 'validation.resource.category.invalid',
                    ]),
                ],
                'type' => [
                    new Assert\Choice([
                        'callback' => [ResourceTypeEnum::class, 'values'],
                        'message' => 'validation.resource.type.invalid',
                    ]),
                ],
            ],
            'allowExtraFields' => true,
        ]);

        $violations = $this->validator->validate($jsonData, $constraints);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }

            return $this->json(['violations' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return null;
    }

    private function validateFile(File $uploadedFile): ?Response
    {
        // As we use a custom controller, the asserts on the DTO are not read so we do it here to avoid 500
        $fileConstraints =
            new Assert\File([
                'maxSize' => self::FILE_MAX_SIZE,
                'mimeTypes' => self::FILE_IMPORT_MIME_TYPES,
                'maxSizeMessage' => 'validation.resource.maxsize',
                'mimeTypesMessage' => 'validation.resource.invalid.format',
        ]);

        $fileViolations = $this->validator->validate($uploadedFile, $fileConstraints);
        if (count($fileViolations) > 0) {
            $fileErrors = [];
            foreach ($fileViolations as $violation) {
                $fileErrors[] = $violation->getMessage();
            }

            return $this->json(['violations' => $fileErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return null;
    }
}
