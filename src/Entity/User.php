<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: "user_name", columns: ["user_name"])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $userName;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $accountEnabled = true;

    #[ORM\OneToOne(targetEntity: UserData::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "user_data_id", referencedColumnName: "id")]
    private UserData $userData;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

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
