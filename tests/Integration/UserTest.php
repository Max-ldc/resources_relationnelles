<?php

namespace Integration;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    public function testGetUserSuccess(): void
    {

// test échoue : http://localhost OK depuis WSL/Windows mais KO depuis Docker ??
// vérifier le "GET" de l'API, il a l'air foireux

        $response = static::createClient()->request('GET', 'http://localhost/api/users');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            [
                'id' => 1,
                'username' => 'Pedrolito',
                'accountEnabled' => true
            ],
            [
                'id' => 2,
                'username' => 'Maria',
                'accountEnabled' => true
            ]
        ]);
    }
}