<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Resource\ResourceCategoryEnum;
use App\Domain\Resource\ResourceSharedStatusEnum;
use App\Domain\Resource\ResourceTypeEnum;
use App\Domain\Resource\UserRoleEnum;
use App\Entity\RelationType;
use App\Entity\Resource;
use App\Entity\ResourceMetadata;
use App\Entity\User;
use App\Entity\UserData;
use App\Repository\RelationTypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(
        private RelationTypeRepository $relationTypeRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 3 Users
        $usernames = ['Pedro', 'Maria', 'UserForDeleteTest'];
        $emailsEncrypted = [
            '9RiA1iLwrzFwGM/E45V8swdw0dQCvPKGs9/5OQhgQGdZ8kBKm4Zlrw7NIDZf3HZi',
            'mZhTA1q/KhjLltgxh9UXn3hRNkhm1nMSwaohh5Ix5WWXSJt4zv1hjtgX+UUm9gPK',
            'FnFbcypAOBl0+s/IVr8KOlFL83Re6dMnOBOVMNAlBcTjWv6xtjNJkDRiM6Djd8wy',
        ];

        foreach ($usernames as $index => $username) {
            $user = $this->createUser($username);
            $userData = $this->createUserData($user, $emailsEncrypted[$index]);
            $user->setUserData($userData);

            $manager->persist($user);
        }

        // Create 2 users with privileges
        $usernames = ['Alberto', 'Sofia'];
        $emailsEncrypted = [
            '5JurgouueiTNMtRrbwHUZ6flH6gxiScOo8FeV/XEusncUbLtoDPYq8oNvzw48E0X',
            'R80wH9Cka9KewU5+WIU2EnLFAbAqSFAz2+fshumzJifMLfIbkABuvK2quaViETG9',
        ];

        $userRoles = [
            'Alberto' => UserRoleEnum::USER_ROLE_CATALOG_ADMIN->value,
            'Sofia' => UserRoleEnum::USER_ROLE_SUPER_ADMIN->value,
        ];

        foreach ($usernames as $index => $username) {
            $user = $this->createUser($username);
            $userData = $this->createUserData($user, $emailsEncrypted[$index]);
            $user->setUserData($userData);

            $user->setRole($userRoles[$username]);

            $manager->persist($user);
        }

        // Create all relation types required by the customer
        $relationTypesData = [
            'Soi' => null,
            'Conjoints' => null,
            'Famille' => null,
            'Professionnel' => null,
            'Amis et communautés' => null,
            'Inconnus' => null,
            'Enfants' => 'Famille',
            'Parents' => 'Famille',
            'Fratrie' => 'Famille',
            'Collègues' => 'Professionnel',
            'Collaborateurs' => 'Professionnel',
            'Managers' => 'Professionnel',
        ];

        $relationTypes = [];
        foreach ($relationTypesData as $type => $parentType) {
            $relationType = $this->createRelationType($type, $relationTypes, $parentType);
            $relationTypes[$type] = $relationType;

            $manager->persist($relationType);
            $manager->flush();
        }

        // Create Resources with their ResourceMetadata
        $relationTypeSoi = $this->relationTypeRepository->findOneBy(['type' => 'Soi']);
        $relationTypeCollegues = $this->relationTypeRepository->findOneBy(['type' => 'Collègues']);
        $relationTypeAmis = $this->relationTypeRepository->findOneBy(['type' => 'Amis et communautés']);

        if (null === $relationTypeSoi || null === $relationTypeCollegues || null === $relationTypeAmis) {
            return;
        }

        $resourcesInfo = [
            [
                'fileName' => 'Extrait - La Boétie.pdf',
                'category' => ResourceCategoryEnum::RESOURCE_CATEGORY_RECHERCHE_SENS->value,
                'type' => ResourceTypeEnum::RESOURCE_TYPE_COURS_PDF->value,
                'author' => 'Etienne de La Boétie',
                'title' => 'Discours de la servitude volontaire',
                'relationTypes' => [$relationTypeSoi, $relationTypeCollegues],
            ],
            [
                'fileName' => 'Manuel d\'Epictète.pdf',
                'category' => ResourceCategoryEnum::RESOURCE_CATEGORY_DEVELOPPEMENT_PERSO->value,
                'type' => ResourceTypeEnum::RESOURCE_TYPE_FICHE_LECTURE->value,
                'author' => 'Epictète',
                'title' => 'Manuel d\'Epictète',
                'relationTypes' => [$relationTypeSoi],
            ],
            [
                'fileName' => 'Le Loup des Steppes.pdf',
                'category' => ResourceCategoryEnum::RESOURCE_CATEGORY_DEVELOPPEMENT_PERSO->value,
                'type' => ResourceTypeEnum::RESOURCE_TYPE_FICHE_LECTURE->value,
                'author' => 'Herman Hesse',
                'title' => 'Le Loup des Steppes',
                'relationTypes' => [$relationTypeSoi, $relationTypeAmis],
            ],
        ];

        foreach ($resourcesInfo as $info) {
            $resource = $this->createResource($userData, $info['fileName'], $info['category'], $info['type']);
            foreach ($info['relationTypes'] as $relationType) {
                $resource->addResourceRelationType($relationType);
            }
            $manager->persist($resource);
            $resourceMetaData = $this->createResourceMetadata($resource, $info['author'], $info['title']);
            $manager->persist($resourceMetaData);
        }

        $manager->flush();
    }

    private function createUser(string $username): User
    {
        $user = new User();
        $user->setUsername($username)
             ->setAccountEnabled(true);

        return $user;
    }

    private function createUserData(User $user, string $emailEncrypted): UserData
    {
        $email = strtolower($user->getUsername()).'@example.com';
        $userData = new UserData();
        $userData->setUser($user)
            ->setEmailEncrypted($emailEncrypted)
            ->setEmailHash(hash('sha256', $email));

        return $userData;
    }

    private function createResource(
        UserData $userData,
        string $fileName,
        string $category,
        string $type,
    ): Resource {
        $resource = new Resource();
        $resource
            ->setUserData($userData)
            ->setFileName($fileName)
            ->setSharedStatus(ResourceSharedStatusEnum::RESOURCE_SHARED_STATUS_PUBLIC->value)
            ->setCategory($category)
            ->setType($type);

        return $resource;
    }

    private function createResourceMetadata(Resource $resource, string $author, string $title): ResourceMetadata
    {
        $resourceMetadata = new ResourceMetadata();
        $resourceMetadata
            ->setResource($resource)
            ->setAuthor($author)
            ->setTitle($title);

        return $resourceMetadata;
    }

    private function createRelationType(string $type, array &$relationTypes, ?string $parentType): RelationType
    {
        $relationType = new RelationType();
        $relationType->setType($type);

        if (null !== $parentType && array_key_exists($parentType, $relationTypes)) {
            $relationType->setParent($relationTypes[$parentType]);
        }

        return $relationType;
    }
}
