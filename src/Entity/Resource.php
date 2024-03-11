<?php

namespace App\Entity;

use App\Doctrine\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity]
#[ORM\Table(name: '`resource`')]
class Resource
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $fileName;

    #[ORM\Column(type: 'resourceSharedStatusType')]
    private string $sharedStatus;

    #[ORM\ManyToOne(targetEntity: UserData::class)]
    #[JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private UserData $userData;

    #[ORM\OneToOne(mappedBy: 'resource', targetEntity: ResourceMetadata::class)]
    private ?ResourceMetadata $resourceMetadata = null;

    #[ORM\Column(type: 'resourceCategoryType')]
    private string $category;

    #[ORM\Column(type: 'resourceTypeType')]
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
