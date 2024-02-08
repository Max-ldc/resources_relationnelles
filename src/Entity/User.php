<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Provider\UserItemDataProvider;
use Doctrine\ORM\Mapping as ORM;

#[Get, GetCollection(provider: UserItemDataProvider::class)]
#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: "username", columns: ["username"])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $username;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $accountEnabled = true;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: UserData::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?UserData $userData = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function isAccountEnabled(): bool
    {
        return $this->accountEnabled;
    }

    public function setAccountEnabled(bool $accountEnabled): self
    {
        $this->accountEnabled = $accountEnabled;

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

}
