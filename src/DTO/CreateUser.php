<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUser
{
    #[Assert\NotBlank(message: 'validation.user.username.empty', groups: ['create_user', 'create_user_with_privileges'])]
    #[Assert\Length(min: 3, max: 16, minMessage: 'validation.user.username.minlength', maxMessage: 'validation.user.username.maxlength')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/', message: 'validation.user.username.regex')]
    private string $username;

    #[Assert\NotBlank(message: 'validation.user.email.empty', groups: ['create_user', 'create_user_with_privileges'])]
    #[Assert\Email(message: 'validation.user.email.invalid')]
    private string $email;

    private bool $accountEnabled = true;

    #[Assert\NotBlank(message: 'validation.user.role.empty', groups: ['create_user_with_privileges'])]
    private ?string $role = null;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
