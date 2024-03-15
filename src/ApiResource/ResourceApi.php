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
                        'application/ld+json' => [
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
                        ],
                    ],
                ],
            ],
            processor: ResourceProcessor::class
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
    #[Assert\NotBlank(message: 'validation.resource.name.empty')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_-]+\.([a-z0-9]+)?$/', message: 'validation.resource.filename.regex')]
    private string $filename;

    // #[Assert\Choice(callback: [ResourceSharedStatusEnum::class, "values"], message: 'validation.resource.sharedStatus.invalid')]
    private string $sharedStatus;

    // #[Assert\Choice(callback: [ResourceTypeEnum::class, "values"], message: 'validation.resource.category.invalid')]
    private string $category;

    // #[Assert\Choice(callback: [ResourceCategoryEnum::class, "values"], message: 'validation.resource.type.invalid')]
    private string $type;

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
}
