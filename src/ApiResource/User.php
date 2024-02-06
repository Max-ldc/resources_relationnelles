<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Processor\UserProcessor;

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
                                    'firstName' => [
                                        'type' => 'string',
                                        'required' => 'true',
                                    ],
                                    'lastName' => [
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
    private string $userName;

    private string $email;

    private string $firstName;

    private string $lastName;

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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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
