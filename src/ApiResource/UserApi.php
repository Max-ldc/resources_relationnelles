<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\User;
use App\Processor\UserPrivilegedProcessor;
use App\Processor\UserProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ApiResource(
        shortName: 'User',
        operations: [
            new Get(
                uriTemplate: '/users/{id}',
                openapiContext: [
                    'parameters' => [
                        [
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => [
                                'type' => 'integer',
                            ],
                            'description' => 'User identifier',
                        ],
                    ],
                    'summary' => 'Retrieves a User resource.',
                    'description' => 'Retrieves a User resource by ID.',
                ],
                class: User::class
            ),
            new GetCollection(),
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
                                        'username' => [
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
            new Delete(
                uriTemplate: '/users/{id}',
                openapiContext: [
                    'parameters' => [
                        [
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => [
                                'type' => 'integer',
                            ],
                            'description' => 'User identifier',
                        ],
                    ],
                    'summary' => 'Removes a User resource.',
                    'description' => 'Removes a User resource by ID.',
                ],
            ),
        ],
        normalizationContext: [
            'groups' => [
                'read_user',
            ],
        ],
        stateOptions: new Options(
            entityClass: User::class
        )
    )
]
class UserApi
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
