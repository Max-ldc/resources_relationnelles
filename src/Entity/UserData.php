<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    /** @var Collection<int, resource> */
    #[ORM\OneToMany(mappedBy: 'userData', targetEntity: Resource::class, cascade: ['persist', 'remove'])]
    private Collection $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
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

    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
        }

        return $this;
    }

    public function removeResources(Resource $resource): void
    {
        if ($this->resources->contains($resource)) {
            $this->resources->removeElement($resource);
        }
    }
}
