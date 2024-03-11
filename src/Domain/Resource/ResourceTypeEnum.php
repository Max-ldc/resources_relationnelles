<?php

declare(strict_types=1);

namespace App\Domain\Resource;

enum ResourceTypeEnum: string
{
    case RESOURCE_TYPE_ARTICLE = 'article';
    case RESOURCE_TYPE_CARTE_DEFI = 'carte_defi';
    case RESOURCE_TYPE_COURS_PDF = 'cours_pdf';
    case RESOURCE_TYPE_EXCERCICE = 'excercice';
    case RESOURCE_TYPE_FICHE_LECTURE = 'fiche_lecture';
    case RESOURCE_TYPE_VIDEO = 'video';
    case RESOURCE_TYPE_AUDIO = 'audio';
    case RESOURCE_TYPE_GAME = 'game';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
