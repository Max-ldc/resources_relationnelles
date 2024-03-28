<?php

namespace Integration;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Storage\FileSystemAdaptor;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ResourceTest extends ApiTestCase
{
    private function uploadFile(string $fileName, string $fileMimeType): UploadedFile
    {
        $filePath = __DIR__.'/../../src/DataFixtures/Files/'.$fileName;

        return new UploadedFile(
            $filePath,
            $fileName,
            $fileMimeType,
        );
    }

    public function testGetCollectionSuccess(): void
    {
        static::createClient()->request('GET', 'http://localhost/api/resources');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertJsonContains([
            '@context' => '/api/contexts/Resource',
            '@id' => '/api/resources',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 3,
            'hydra:member' => [
                [
                    '@id' => '/api/resources/1',
                    '@type' => 'Resource',
                    'id' => 1,
                    'fileName' => 'Extrait - La Boétie.pdf',
                    'sharedStatus' => 'public',
                    'userData' => [
                        '@id' => '/api/user_datas/5',
                        '@type' => 'UserData',
                        'user' => [
                            '@id' => '/api/users/5',
                            '@type' => 'User',
                            'username' => 'Sofia',
                        ],
                    ],
                    'resourceMetadata' => [
                        '@id' => '/api/resource_metadatas/1',
                        '@type' => 'ResourceMetadata',
                        'title' => 'Discours de la servitude volontaire',
                        'author' => 'Etienne de La Boétie',
                    ],
                    'category' => 'recherche_de_sens',
                    'type' => 'cours_pdf',
                ],
                [
                    '@id' => '/api/resources/2',
                    '@type' => 'Resource',
                    'id' => 2,
                    'fileName' => "Manuel d'Epictète.pdf",
                    'sharedStatus' => 'public',
                    'userData' => [
                        '@id' => '/api/user_datas/5',
                        '@type' => 'UserData',
                        'user' => [
                            '@id' => '/api/users/5',
                            '@type' => 'User',
                            'username' => 'Sofia',
                        ],
                    ],
                    'resourceMetadata' => [
                        '@id' => '/api/resource_metadatas/2',
                        '@type' => 'ResourceMetadata',
                        'title' => "Manuel d'Epictète",
                        'author' => 'Epictète',
                    ],
                    'category' => 'developpement_personnel',
                    'type' => 'fiche_lecture',
                ],
                [
                    '@id' => '/api/resources/3',
                    '@type' => 'Resource',
                    'id' => 3,
                    'fileName' => 'Le Loup des Steppes.pdf',
                    'sharedStatus' => 'public',
                    'userData' => [
                        '@id' => '/api/user_datas/5',
                        '@type' => 'UserData',
                        'user' => [
                            '@id' => '/api/users/5',
                            '@type' => 'User',
                            'username' => 'Sofia',
                        ],
                    ],
                    'resourceMetadata' => [
                        '@id' => '/api/resource_metadatas/3',
                        '@type' => 'ResourceMetadata',
                        'title' => 'Le Loup des Steppes',
                        'author' => 'Herman Hesse',
                    ],
                    'category' => 'developpement_personnel',
                    'type' => 'fiche_lecture',
                ],
            ],
        ]);
    }

    public function testGetResourceSuccess(): void
    {
        static::createClient()->request('GET', 'http://localhost/api/resources/1');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertJsonContains([
            '@context' => '/api/contexts/Resource',
            '@id' => '/api/resources/1',
            '@type' => 'Resource',
            'id' => 1,
            'fileName' => 'Extrait - La Boétie.pdf',
            'sharedStatus' => 'public',
            'userData' => [
                '@id' => '/api/user_datas/5',
                '@type' => 'UserData',
                'user' => [
                    '@id' => '/api/users/5',
                    '@type' => 'User',
                    'username' => 'Sofia',
                ],
            ],
            'resourceMetadata' => [
                '@id' => '/api/resource_metadatas/1',
                '@type' => 'ResourceMetadata',
                'title' => 'Discours de la servitude volontaire',
                'author' => 'Etienne de La Boétie',
            ],
            'category' => 'recherche_de_sens',
            'type' => 'cours_pdf',
        ]);
    }

    public function testGetResourceFails(): void
    {
        $client = static::createClient();
        $client->request('GET', 'http://localhost/api/resources/999');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testPostResourceSuccess(): void
    {
        $fileName = 'Alain - Du devoir d\'être heureux.pdf';
        $fileMimeType = 'application/pdf';
        $uploadedFile = $this->uploadFile($fileName, $fileMimeType);

        $client = static::createClient();
        $client->request('POST', 'http://localhost/api/resources', [
            'headers' => [
                'Content-Type' => 'multipart/form-data'],
            'extra' => [
                'files' => [
                    'importFile' => $uploadedFile,
                ],
                'parameters' => [
                    'json' => json_encode([
                        'sharedStatus' => 'public',
                        'category' => 'loisirs',
                        'type' => 'cours_pdf',
                        'title' => 'Propos sur le bonheur',
                        'author' => 'Alain',
                        'userData' => '/api/user_datas/1',
                        'relationTypes' => [
                            '/api/relation_types/1',
                            ],
                    ], JSON_THROW_ON_ERROR),
                ],
            ],
        ]);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $fileSystemAdaptor = static::getContainer()->get(FileSystemAdaptor::class);

        $fileContent = $fileSystemAdaptor->getFileContent($fileName);
        $this->assertNotEmpty($fileContent, 'The file content should not be empty');
    }

    /**
     * @dataProvider PostResourceBadValueDataProvider
     */
    public function testPostResourceBadValueFails(array $data, array $expectedViolations): void
    {
        $fileName = 'Alain - Du devoir d\'être heureux.pdf';
        $fileMimeType = 'application/pdf';
        $uploadedFile = $this->uploadFile($fileName, $fileMimeType);

        $client = static::createClient();
        $client->request('POST', 'http://localhost/api/resources', [
            'headers' => [
                'Content-Type' => 'multipart/form-data'],
            'extra' => [
                'files' => [
                    'importFile' => $uploadedFile,
                ],
                'parameters' => [
                    'json' => json_encode($data, JSON_THROW_ON_ERROR),
                ],
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function PostResourceBadValueDataProvider(): \Generator
    {
        $expectedViolations = [
            '[sharedStatus]' => [
                "Le statut de partage de la ressource n'est pas reconnu.",
            ],
        ];

        $data = [
            'sharedStatus' => '',
            'category' => 'loisirs',
            'type' => 'cours_pdf',
            'title' => 'Propos sur le bonheur',
            'author' => 'Alain',
            'userData' => '/api/user_datas/1',
            'relationTypes' => ['/api/relation_types/1'],
        ];
        yield 'Post fails - Bad shared status' => [$data, $expectedViolations];

        $expectedViolations = [
            '[category]' => [
                "La catégorie de la ressource n'est pas reconnue",
            ],
        ];

        $data = [
            'sharedStatus' => 'public',
            'category' => '',
            'type' => 'cours_pdf',
            'title' => 'Propos sur le bonheur',
            'author' => 'Alain',
            'userData' => '/api/user_datas/1',
            'relationTypes' => ['/api/relation_types/1'],
        ];
        yield 'Post fails - Empty category' => [$data, $expectedViolations];

        $expectedViolations = [
            '[type]' => [
                "Le type de ressource n'est pas reconnu",
            ],
        ];

        $data = [
            'sharedStatus' => 'public',
            'category' => 'loisirs',
            'type' => '',
            'title' => 'Propos sur le bonheur',
            'author' => 'Alain',
            'userData' => '/api/user_datas/1',
            'relationTypes' => ['/api/relation_types/1'],
        ];
        yield 'Post fails - Empty type status' => [$data, $expectedViolations];

        $expectedViolations = [
            '[title]' => [
                'Le titre de la ressource ne peut pas être vide',
            ],
        ];

        $data = [
            'sharedStatus' => 'public',
            'category' => 'loisirs',
            'type' => 'cours_pdf',
            'title' => '',
            'author' => 'Alain',
            'userData' => '/api/user_datas/1',
            'relationTypes' => ['/api/relation_types/1'],
        ];
        yield 'Post fails - Empty title' => [$data, $expectedViolations];
    }

    /**
     * @dataProvider postResourceFileUploadFailsDataProvider
     */
    public function testPostResourceFileUploadFails(string $fileName, string $fileMimeType, array $expectedViolations): void
    {
        $uploadedFile = $this->uploadFile($fileName, $fileMimeType);

        $data = [
            'sharedStatus' => 'private',
            'category' => 'loisirs',
            'type' => 'fiche_de_lecture',
            'title' => "J'accuse",
            'author' => 'Victor Hugo',
            'userData' => '/api/user_datas/1',
            'relationTypes' => ['/api/relation_types/1'],
        ];

        $client = static::createClient();
        $client->request('POST', 'http://localhost/api/resources', [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'files' => ['importFile' => $uploadedFile],
                'parameters' => ['json' => json_encode($data, JSON_THROW_ON_ERROR)],
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertJsonContains([
            'violations' => $expectedViolations,
        ]);
    }

    public static function postResourceFileUploadFailsDataProvider(): \Generator
    {
        $fileName = 'jaccuse.docx';
        $fileMimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        $expectedViolations = [
            'Le format est invalide',
        ];
        yield 'Upload fails - Wrong format' => [$fileName, $fileMimeType, $expectedViolations];

        $fileName = 'bates_design-patterns-tete-la-premiere.pdf';
        $fileMimeType = 'application/vnd.ms-excel';
        $expectedViolations = [
            'Le fichier est trop volumineux',
        ];
        yield 'Create with bad columns in file' => [$fileName, $fileMimeType, $expectedViolations];
    }
}
