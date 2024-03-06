<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity]
#[ORM\Table(name: '`resource`')]
class Resource
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $fileName;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $creationDate;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $modificationDate = null;

    #[ORM\Column(type: 'resourceSharedStatusType')]
    private string $sharedStatus;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\OneToOne(mappedBy: 'resource', targetEntity: ResourceMetadata::class)]
    private ?ResourceMetadata $resourceMetadata = null;

    #[ORM\Column(type: 'resourceCategoryType')]
    private string $category;

    #[ORM\Column(type: 'resourceTypeType')]
    private string $type;

    #[ORM\ManyToMany(targetEntity: RelationType::class, inversedBy: 'resources')]
    #[ORM\JoinTable(name: 'resource_relation_type')]
    private Collection $resourceRelationTypes;

    #[ORM\ManyToMany(targetEntity: UserData::class, mappedBy: 'favoriteResources')]
    #[ORM\JoinTable(joinColumns: [
        new JoinColumn(name: 'resource_id', referencedColumnName: 'id', onDelete: 'CASCADE'),
        new JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE'),
    ])]
    private Collection $usersWhoAddedThisResourceToFavorite;

    public function __construct() {
        $this->resourceRelationTypes = new ArrayCollection();
        $this->usersWhoAddedThisResourceToFavorite = new ArrayCollection();
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

    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTime $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getModificationDate(): ?\DateTime
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?\DateTime $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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

    public function getUsersWhoAddedThisResourceToFavorite(): Collection
    {
        return $this->usersWhoAddedThisResourceToFavorite;
    }

    public function setUsersWhoAddedThisResourceToFavorite(Collection $usersWhoAddedThisResourceToFavorite): self
    {
        $this->usersWhoAddedThisResourceToFavorite = $usersWhoAddedThisResourceToFavorite;

        return $this;
    }

    public function addUserWhoAddedThisResourceToFavorite(UserData $userData): self
    {
        if (!$this->usersWhoAddedThisResourceToFavorite->contains($userData)) {
            $this->usersWhoAddedThisResourceToFavorite[] = $userData;
            $userData->addFavoriteResource($this);
        }

        return $this;
    }

    public function removeUserWhoAddedThisResourceToFavorite(UserData $userData): self
    {
        if ($this->usersWhoAddedThisResourceToFavorite->contains($userData)) {
            $this->usersWhoAddedThisResourceToFavorite->removeElement($userData);
            $userData->removeFavoriteResource($this);
        }

        return $this;
    }
}