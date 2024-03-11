<?php

declare(strict_types=1);

namespace App\Domain\Resource;

enum ResourceSharedStatusEnum: string
{
    case RESOURCE_SHARED_STATUS_PUBLIC = 'public';
    case RESOURCE_CATEGORY_SHARED = 'shared';
    case RESOURCE_CATEGORY_PRIVATE = 'private';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
