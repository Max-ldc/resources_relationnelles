<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\CreateResourceController;
use App\Controller\ReadResourceController;
use App\Doctrine\Traits\TimestampTrait;
use App\Repository\ResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    shortName: 'Resource',
    operations: [
        new Get(),
        new GetCollection(),
        new Get(
            uriTemplate: '/resources/{id}/read',
            formats: ['pdf' => ['application/pdf']],
            controller: ReadResourceController::class,
            openapiContext: [
                'summary' => 'Read the resource PDF',
                'description' => 'Streams the PDF file for the resource.',
                'responses' => [
                    '200' => [
                        'description' => 'PDF file streamed successfully',
                        'content' => [
                            'application/pdf' => [
                                'schema' => [
                                    'type' => 'string',
                                    'format' => 'binary',
                                ]
                            ]
                        ]
                    ],
                    '404' => [
                        'description' => 'Resource not found'
                    ]
                ]
            ],
            output: false,
            read: false,
        ),
        new Post(
            inputFormats: [
                'multipart' => [
                    'multipart/form-data',
                ],
            ],
            status: Response::HTTP_CREATED,
            controller: CreateResourceController::class,
            openapiContext: [
                'summary' => 'Create a new resource',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'json' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'sharedStatus' => [
                                                'type' => 'string',
                                                'required' => 'true',
                                            ],
                                            'category' => [
                                                'type' => 'string',
                                                'required' => 'true',
                                            ],
                                            'type' => [
                                                'type' => 'string',
                                                'required' => 'true',
                                            ],
                                            'title' => [
                                                'type' => 'string',
                                                'required' => 'true',
                                            ],
                                            'author' => [
                                                'type' => 'string',
                                                'required' => 'true',
                                            ],
                                            'userData' => [
                                                'type' => 'string',
                                                'required' => 'true',
                                                'description' => 'IRI reference to userData',
                                            ],
                                            'relationTypes' => [
                                                'type' => 'array',
                                                'required' => 'true',
                                                'items' => [
                                                    'type' => 'string',
                                                ],
                                                'description' => 'Array of IRI references to relation types',
                                            ],
                                        ],
                                    ],
                                    'importFile' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                            'encoding' => [
                                'json' => [
                                    'contentType' => 'application/json',
                                ],
                                'importFile' => [
                                    'contentType' => 'multipart/form-data',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            normalizationContext: ['groups' => []],
            denormalizationContext: ['groups' => ['create_resource']],
            input: CreateResourceController::class,
            deserialize: false,
        ),
        new Delete(
            uriTemplate: '/resources/{id}',
            openapiContext: [
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer',
                        ],
                        'description' => 'Resource identifier',
                    ],
                ],
                'summary' => 'Removes a Resource item.',
                'description' => 'Removes a Resource item by ID.',
            ],
        ),
    ],
    normalizationContext: [
        'groups' => [
            'read_resource',
        ],
    ],
)]
#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ORM\Table(name: '`resource`')]
class Resource
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    #[Groups(['read_resource'])]
    private int $id;

    #[ORM\Column(type: 'string')]
    #[Groups(['read_resource'])]
    private string $fileName;

    #[ORM\Column(type: 'resourceSharedStatusType')]
    #[Groups(['read_resource'])]
    private string $sharedStatus;

    #[ORM\ManyToOne(targetEntity: UserData::class)]
    #[JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['read_resource'])]
    private UserData $userData;

    #[ORM\OneToOne(mappedBy: 'resource', targetEntity: ResourceMetadata::class, cascade: ['persist', 'remove'])]
    #[Groups(['read_resource'])]
    private ?ResourceMetadata $resourceMetadata = null;

    #[ORM\Column(type: 'resourceCategoryType')]
    #[Groups(['read_resource'])]
    private string $category;

    #[ORM\Column(type: 'resourceTypeType')]
    #[Groups(['read_resource'])]
    private string $type;

    #[ORM\ManyToMany(targetEntity: RelationType::class, inversedBy: 'resources')]
    #[ORM\JoinTable(name: 'resource_relation_type')]
    private Collection $resourceRelationTypes;

    public function __construct()
    {
        $this->resourceRelationTypes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getSharedStatus(): string
    {
        return $this->sharedStatus;
    }

    public function setSharedStatus(string $sharedStatus): self
    {
        $this->sharedStatus = $sharedStatus;

        return $this;
    }

    public function getUserData(): UserData
    {
        return $this->userData;
    }

    public function setUserData(UserData $userData): self
    {
        $this->userData = $userData;

        return $this;
    }

    public function getResourceMetadata(): ?ResourceMetadata
    {
        return $this->resourceMetadata;
    }

    public function setResourceMetadata(?ResourceMetadata $resourceMetadata): self
    {
        $this->resourceMetadata = $resourceMetadata;

        $resourceMetadata?->setResource($this);

        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getResourceRelationTypes(): Collection
    {
        return $this->resourceRelationTypes;
    }

    public function setResourceRelationTypes(Collection $resourceRelationTypes): self
    {
        $this->resourceRelationTypes = $resourceRelationTypes;

        return $this;
    }

    public function addResourceRelationType(RelationType $relationType): self
    {
        if (!$this->resourceRelationTypes->contains($relationType)) {
            $this->resourceRelationTypes[] = $relationType;
            $relationType->addResource($this);
        }

        return $this;
    }

    public function removeResourceRelationType(RelationType $relationType): self
    {
        if ($this->resourceRelationTypes->contains($relationType)) {
            $this->resourceRelationTypes->removeElement($relationType);
            $relationType->removeResource($this);
        }

        return $this;
    }
}
