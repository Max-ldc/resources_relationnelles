<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Domain\Resource\UserRoleEnum;
use App\DTO\CreateUser;
use App\Processor\UserPrivilegedProcessor;
use App\Processor\UserProcessor;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'username', columns: ['username'])]
#[ApiResource(
    operations: [
        new Get(),
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
            validationContext: [
                'groups' => [
                    'create_user',
                ],
            ],
            input: CreateUser::class,
            processor: UserProcessor::class
        ),
        new Post(
            uriTemplate: '/users_privileged',
            openapiContext: [
                'summary' => 'Create a new user with privileges',
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
                                    'role' => [
                                        'type' => 'string',
                                        'required' => 'true',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            validationContext: [
                'groups' => [
                    'create_user_with_privileges',
                ],
            ],
            input: CreateUser::class,
            processor: UserPrivilegedProcessor::class
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
        new Patch(
            uriTemplate: '/users/{id}',
            openapiContext: [
                'summary' => 'Update an existing user',
                'description' => 'Enables or disables an existing user'
                ],
            denormalizationContext: ['groups' => ['update_user']],
            validationContext: ['groups' => ['update_user']],
        ),
    ],
    normalizationContext: [
        'groups' => [
            'read_user',
        ],
    ],
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    #[Groups(['read_user'])]
    private int $id;

    #[ORM\Column(type: 'string')]
    #[Groups(['read_user', 'read_resource'])]
    private string $username;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Groups(['read_user', 'update_user'])]
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
