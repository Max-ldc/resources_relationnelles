<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Domain\Resource\ResourceCategoryEnum;
use App\Domain\Resource\ResourceSharedStatusEnum;
use App\Domain\Resource\ResourceTypeEnum;
use App\Entity\Resource;
use App\Processor\ResourceProcessor;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Resource',
    operations: [
        new Get(
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
                'summary' => 'Retrieves a Resource item',
                'description' => 'Retrieves a Resource item by ID'
            ],
            class: Resource::class
        ),
        new GetCollection(),
        new Post(
            openapiContext: [
                'summary' => 'Create a new resource',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'filename' => [
                                        'type' => 'string',
                                        'required' => 'true',
                                    ],
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
            processor: ResourceProcessor::class,
            inputFormats: ['multipart' => ['multipart/form-data']],
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
        )
    ],
    normalizationContext: [
        'groups' => [
            'read_resource'
        ],
    ],
    stateOptions: new Options(
        entityClass: Resource::class
    )
)]

class ResourceApi
{
    private const FILE_IMPORT_MIME_TYPES = [
        'application/pdf',
    ];

    #[Assert\NotBlank(message: 'validation.resource.filename.empty')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_-]+\.([a-z0-9]+)?$/', message: 'validation.resource.filename.regex')]
    private string $filename;

    #[Assert\Choice(callback: [ResourceSharedStatusEnum::class, "values"], message: 'validation.resource.sharedStatus.invalid')]
    private string $sharedStatus;

    #[Assert\Choice(callback: [ResourceCategoryEnum::class, "values"], message: 'validation.resource.category.invalid')]
    private string $category;

    #[Assert\Choice(callback: [ResourceTypeEnum::class, "values"], message: 'validation.resource.type.invalid')]
    private string $type;

    // ResourceMetadatas :

    #[Assert\NotBlank(message: 'validation.resource.title.empty')]
    private string $title;

    #[Assert\NotBlank(groups: ['upload_audio', 'upload_video', 'read_resource'])]
    private ?int $duration = null;

    private ?string $format = null;

    #[Assert\NotBlank(groups: ['create_resource_pdf'])]
    private ?string $author = null;

    private ?string $album = null;

    #[Assert\NotBlank(groups: ['upload_audio', 'upload_video', 'read_resource'])]
    private ?string $genre = null;

    #[Assert\File(maxSize: 8_000_000, mimeTypes: self::FILE_IMPORT_MIME_TYPES, mimeTypesMessage: 'validation.resource.invalid.format')]
    private ?File $importFile = null;

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(?string $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->importFile;
    }

    public function setFile(?File $importFile): self
    {
        $this->importFile = $importFile;
        return $this;
    }
}
