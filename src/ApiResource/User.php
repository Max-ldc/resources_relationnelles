<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Processor\UserProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Get(),
        new Post(
            openapiContext: [
                'summary' => 'Create a new user',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'userName' => [
                                        'type' => 'string',
                                        'required' => 'true',
                                    ],
                                    'email' => [
                                        'type' => 'string',
                                        'required' => 'true',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            processor: UserProcessor::class
        ),
        new Delete(),
    ])
]
class User
{
    #[Assert\NotBlank(message: 'validation.user.username.empty')]
    #[Assert\Length(min: 3, max: 16, minMessage: 'validation.user.username.minlength', maxMessage: 'validation.user.username.maxlength')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/', message: 'validation.user.username.regex')]
    private string $userName;

    #[Assert\NotBlank(message: 'validation.user.email.empty')]
    #[Assert\Email(message: 'validation.user.email.invalid')]
    private string $email;

    private bool $accountEnabled = true;

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

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
}
