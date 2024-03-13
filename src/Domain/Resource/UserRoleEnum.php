<?php

declare(strict_types=1);

namespace App\Domain\Resource;

enum UserRoleEnum: string
{
    case USER_ROLE_CONNECTED_CITIZEN = 'citoyen connecté';
    case USER_ROLE_MODERATOR = 'modérateur';
    case USER_ROLE_CATALOG_ADMIN = 'administrateur';
    case USER_ROLE_SUPER_ADMIN = 'super administrateur';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
