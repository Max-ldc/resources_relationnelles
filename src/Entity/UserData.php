<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity]
#[ORM\Table(name: '`user_data`')]
#[ORM\UniqueConstraint(name: 'email_hash', columns: ['email_hash'])]
class UserData
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $emailEncrypted;

    #[ORM\Column(type: 'string', unique: true)]
    private string $emailHash;

    #[ORM\OneToOne(inversedBy: 'userData', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    /**
     * @var Collection<int, Resource>
     */
    #[ORM\ManyToMany(targetEntity: Resource::class, inversedBy: 'usersWhoAddedThisResourceToFavorite')]
    private Collection $favoriteResources;

    public function __construct() {
        $this->favoriteResources = new ArrayCollection();
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

    public function getEmailEncrypted(): string
    {
        return $this->emailEncrypted;
    }

    public function setEmailEncrypted(string $emailEncrypted): self
    {
        $this->emailEncrypted = $emailEncrypted;

        return $this;
    }

    public function getEmailHash(): string
    {
        return $this->emailHash;
    }

    public function setEmailHash(string $emailHash): self
    {
        $this->emailHash = $emailHash;

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

    public function getFavoriteResources(): Collection
    {
        return $this->favoriteResources;
    }

    public function setFavoriteResources(Collection $favoriteResources): self
    {
        $this->favoriteResources = $favoriteResources;

        return $this;
    }

    public function addFavoriteResource(Resource $resource): self
    {
        if (!$this->favoriteResources->contains($resource)) {
            $this->favoriteResources[] = $resource;
            $resource->addUserWhoAddedThisResourceToFavorite($this);
        }

        return $this;
    }

    public function removeFavoriteResource(Resource $resource): void
    {
        if ($this->favoriteResources->contains($resource)) {
            $this->favoriteResources->removeElement($resource);
            $resource->removeUserWhoAddedThisResourceToFavorite($this);
        }
    }
}
