<?php

declare(strict_types=1);

namespace App\Doctrine\DBAL\Types;

use App\Domain\Resource\ResourceCategoryEnum;

final class ResourceCategoryType extends AbstractEnumType
{
    protected string $name = 'resourceCategoryType';

    protected function getValues(): array
    {
        return ResourceCategoryEnum::values();
    }
}
