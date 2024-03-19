<?php

namespace Integration;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserDataRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends ApiTestCase
{
    public function testGetCollectionSuccess(): void
    {
        static::createClient()->request('GET', 'http://localhost/api/users');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertJsonContains([
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 5,
            'hydra:member' => [
                [
                    '@type' => 'User',
                    'id' => 1,
                    'username' => 'Pedro',
                    'accountEnabled' => true,
                ],
                [
                    '@type' => 'User',
                    'id' => 2,
                    'username' => 'Maria',
                    'accountEnabled' => true,
                ],
                [
                    '@type' => 'User',
                    'id' => 3,
                    'username' => 'UserForDeleteTest',
                    'accountEnabled' => true,
                ],
            ],
        ]);
    }

    public function testGetUserSuccess(): void
    {
        static::createClient()->request('GET', 'http://localhost/api/users/2');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertJsonContains([
            '@type' => 'User',
            'id' => 2,
            'username' => 'Maria',
            'accountEnabled' => true,
            'role' => 'citoyen connecté',
        ]);
    }

    public function testGetUserFails(): void
    {
        $client = static::createClient();
        $client->request('GET', 'http://localhost/api/users/999');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testPostUserSuccess(): void
    {
        $client = static::createClient();
        $client->request('POST', 'http://localhost/api/users', [
            'headers' => [
                'Content-Type' => 'application/ld+json'],
            'body' => json_encode([
                'username' => 'Juan',
                'email' => 'juan@example.com',
            ], JSON_THROW_ON_ERROR),
        ]);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    /**
     * @dataProvider PostUserDuplicateDataProvider
     */
    public function testPostUserDuplicateFails(array $data): void
    {
        $client = static::createClient();
        $client->request('POST', 'http://localhost/api/users', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $data,
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public static function PostUserDuplicateDataProvider(): \Generator
    {
        yield 'Post fails - Duplicate email' => [
            [
                'username' => 'MariaDu06',
                'email' => 'maria@example.com',
            ],
        ];

        yield 'Post fails - Duplicate username' => [
            [
                'username' => 'Maria',
                'email' => 'mariadu06@example.com',
            ],
        ];
    }

    /**
     * @dataProvider PostUserNoMandatoryValueDataProvider
     */
    public function testPostUserNoMandatoryValueFails(array $data, array $expectedViolations): void
    {
        $client = static::createClient();
        $client->request('POST', 'http://localhost/api/users', [
            'headers' => [
                'Content-Type' => 'application/ld+json'],
            'json' => $data,
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertJsonContains([
            'violations' => $expectedViolations,
        ]);
    }

    public static function PostUserNoMandatoryValueDataProvider(): \Generator
    {
        $expectedViolations = [
            [
                'message' => 'L\'adresse mail ne peut pas être vide.',
            ],
        ];
        $data = [
            'username' => 'Carlos',
            'email' => '',
        ];
        yield 'Post fails - Empty email' => [$data, $expectedViolations];

        $expectedViolations = [
            [
                'message' => 'Le nom d\'utilisateur ne peut pas être vide.',
            ],
        ];
        $data = [
            'username' => '',
            'email' => 'carlos@example.com',
        ];
        yield 'Post fails - Empty username' => [$data, $expectedViolations];
    }

    public function testPostUserWithPrivilegesSuccess(): void
    {
        $client = static::createClient();
        $client->request('POST', 'http://localhost/api/users_privileged', [
            'headers' => [
                'Content-Type' => 'application/ld+json'],
            'body' => json_encode([
                'username' => 'Valentina',
                'email' => 'valentina@example.com',
                'role' => 'modérateur',
            ], JSON_THROW_ON_ERROR),
        ]);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    /**
     * @dataProvider PostUserWithPrivilegesNoMandatoryValueDataProvider
     */
    public function testPostUserWithPrivilegesFails($data, $expectedViolations): void
    {
        $client = static::createClient();
        $client->request('POST', 'http://localhost/api/users_privileged', [
            'headers' => [
                'Content-Type' => 'application/ld+json'],
            'json' => $data,
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertJsonContains([
            'violations' => $expectedViolations,
        ]);
    }

    public static function PostUserWithPrivilegesNoMandatoryValueDataProvider(): \Generator
    {
        $expectedViolations = [
            [
                'message' => 'L\'adresse mail ne peut pas être vide.',
            ],
        ];
        $data = [
            'username' => 'Carlos',
            'email' => '',
            'role' => 'administrateur',
        ];
        yield 'Post fails - Empty email' => [$data, $expectedViolations];

        $expectedViolations = [
            [
                'message' => 'Le nom d\'utilisateur ne peut pas être vide.',
            ],
        ];
        $data = [
            'username' => '',
            'email' => 'carlos@example.com',
            'role' => 'administrateur',
        ];
        yield 'Post fails - Empty username' => [$data, $expectedViolations];

        $expectedViolations = [
            [
                'message' => 'Le rôle ne peut pas être vide.',
            ],
        ];
        $data = [
            'username' => 'antonio',
            'email' => 'antonio@example.com',
            'role' => '',
        ];
        yield 'Post fails - Empty role' => [$data, $expectedViolations];
    }

    public function testDeleteUserByIdSuccess(): void
    {
        $userToDelete = 'UserForDeleteTest';
        $userRepository = self::getContainer()->get(UserRepository::class);
        $userDataRepository = self::getContainer()->get(UserDataRepository::class);

        $user = $userRepository->findOneBy(['username' => $userToDelete]);
        $userId = $user->getId();

        $client = static::createClient();
        $client->request('DELETE', "http://localhost/api/users/{$userId}");

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $deletedUser = $userRepository->find($userId);
        self::assertNull($deletedUser);

        $deletedUserData = $userDataRepository->findOneBy(['user' => $userId]);
        self::assertNull($deletedUserData);
    }
}
