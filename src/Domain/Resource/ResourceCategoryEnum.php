<?php

declare(strict_types=1);

namespace App\Domain\Resource;

enum ResourceCategoryEnum: string
{
    case RESOURCE_CATEGORY_COMMUNICATION = 'communication';
    case RESOURCE_CATEGORY_CULTURE = 'culture';
    case RESOURCE_CATEGORY_DEVELOPPEMENT_PERSO = 'developpement_personnel';
    case RESOURCE_CATEGORY_INTELLIGENCE_EMO = 'intelligence_emotionnelle';
    case RESOURCE_CATEGORY_LOISIRS = 'loisirs';
    case RESOURCE_CATEGORY_MONDE_PRO = 'monde_professionnel';
    case RESOURCE_CATEGORY_PARENTALITE = 'parentalite';
    case RESOURCE_CATEGORY_QUALITE_VIE = 'qualite_de_vie';
    case RESOURCE_CATEGORY_RECHERCHE_SENS = 'recherche_de_sens';
    case RESOURCE_CATEGORY_SANTE_PHYSIQUE = 'sante_physique';
    case RESOURCE_CATEGORY_SANTE_PSYCHIQUE = 'sante_psychique';
    case RESOURCE_CATEGORY_SPIRITUALITE = 'spiritualite';
    case RESOURCE_CATEGORY_VIE_AFFECTIVE = 'vie_affective';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
