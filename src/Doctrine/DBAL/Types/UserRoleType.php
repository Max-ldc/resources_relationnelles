<?php

declare(strict_types=1);

namespace App\Doctrine\DBAL\Types;

use App\Domain\Resource\UserRoleEnum;

final class UserRoleType extends AbstractEnumType
{
    protected string $name = 'userRoleType';

    protected function getValues(): array
    {
        return UserRoleEnum::values();
    }
}
