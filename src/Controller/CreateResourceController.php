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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[AsController] // use a custom Controller because ApiPlatform cannot natively handle multipart
class CreateResourceController extends AbstractController
{
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

        $originalFileName = $uploadedFile->getClientOriginalName();
        $filePath = $uploadedFile->getRealPath();
        $jsonData = json_decode($request->get('json'), true, 512, JSON_THROW_ON_ERROR);

        //Validate data
        $validationResponse = $this->validateData($jsonData);
        if ($validationResponse !== null) {
            return $validationResponse;
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
        // As we use a custom controller, the asserts on the DTO are not read so we do it here
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
}
