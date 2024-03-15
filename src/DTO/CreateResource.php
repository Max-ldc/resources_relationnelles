<?php

declare(strict_types=1);

namespace App\DTO;

use App\Domain\Resource\ResourceCategoryEnum;
use App\Domain\Resource\ResourceSharedStatusEnum;
use App\Domain\Resource\ResourceTypeEnum;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class CreateResource
{
    // To add a accepted file format, add the MIME Type in the following array
    private const FILE_IMPORT_MIME_TYPES = [
        'application/pdf',
    ];

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

    #[Assert\NotBlank(groups: ['create_resource_audio', 'create_resource_video'])]
    private ?int $duration = null;

    private ?string $format = null;

    #[Assert\NotBlank(groups: ['create_resource_pdf'])]
    private ?string $author = null;

    #[Assert\NotBlank(groups: ['create_resource_audio'])]
    private ?string $album = null;

    #[Assert\NotBlank(groups: ['create_resource_audio', 'create_resource_video'])]
    private ?string $genre = null;

    #[Assert\File(maxSize: 8_000_000, mimeTypes: self::FILE_IMPORT_MIME_TYPES, mimeTypesMessage: 'validation.resource.invalid.format', maxSizeMessage: 'validation.resource.maxsize')]
    #[Assert\NotBlank(message: 'validation.resource.file.empty')]
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
