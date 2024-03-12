<?php

declare(strict_types=1);

namespace App\Doctrine\DBAL\Types;

use App\Domain\Resource\ResourceTypeEnum;

final class ResourceTypeType extends AbstractEnumType
{
    protected string $name = 'resourceTypeType';

    protected function getValues(): array
    {
        return ResourceTypeEnum::values();
    }
}
