<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Domain\Resource\UserRoleEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'username', columns: ['username'])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    #[Groups(['read_user'])]
    private int $id;

    #[ORM\Column(type: 'string')]
    #[Groups(['read_user'])]
    private string $username;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Groups(['read_user'])]
    private bool $accountEnabled = true;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: UserData::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups('read_user_with_user_details')]
    private ?UserData $userData = null;

    #[ORM\Column(type: 'userRoleType', options: ['default' => UserRoleEnum::USER_ROLE_CONNECTED_CITIZEN->value])]
    #[Groups(['read_user'])]
    private string $role;

    public function __construct()
    {
        $this->role = UserRoleEnum::USER_ROLE_CONNECTED_CITIZEN->value;
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

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
