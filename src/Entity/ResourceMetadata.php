<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Doctrine\Traits\TimestampTrait;
use App\Repository\ResourceMetadataRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    normalizationContext: [
        'groups' => [
            'read_user_data',
        ],
    ],
)]
#[ORM\Entity(repositoryClass: ResourceMetadataRepository::class)]
#[ORM\Table(name: '`resource_metadata`')]
class ResourceMetadata
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    private int $id;

    #[ORM\Column(type: 'string')]
    #[Groups(['upload_audio', 'upload_video', 'upload_pdf', 'read_resource'])]
    private string $title;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['upload_audio', 'upload_video', 'read_resource'])]
    private ?int $duration = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(['upload_audio', 'upload_video', 'upload_pdf', 'read_resource'])]
    private ?string $format = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(['upload_audio', 'upload_video', 'upload_pdf', 'read_resource'])]
    private ?string $author = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(['upload_audio', 'read_resource'])]
    private ?string $album = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(['upload_audio', 'upload_video', 'read_resource'])]
    private ?string $genre = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['upload_audio', 'upload_video', 'upload_pdf', 'read_resource'])]
    private ?\DateTime $releaseDate = null;

    #[ORM\OneToOne(inversedBy: 'resourceMetadata', targetEntity: Resource::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Resource $resource;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getReleaseDate(): \DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTime $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getResource(): Resource
    {
        return $this->resource;
    }

    public function setResource(Resource $resource): self
    {
        $this->resource = $resource;

        return $this;
    }
}
