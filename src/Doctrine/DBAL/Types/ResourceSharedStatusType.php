<?php

declare(strict_types=1);

namespace App\Doctrine\DBAL\Types;

use App\Domain\Resource\ResourceSharedStatusEnum;

final class ResourceSharedStatusType extends AbstractEnumType
{
    protected string $name = 'resourceSharedStatusType';

    protected function getValues(): array
    {
        return ResourceSharedStatusEnum::values();
    }
}
