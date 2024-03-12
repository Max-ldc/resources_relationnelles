<?php

declare(strict_types=1);

namespace App\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Types\Type;

abstract class AbstractEnumType extends Type
{
    protected string $name;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $sqlDeclaration = $this->buildDefaultSQLDeclaration($column, $platform);

        $defaultValue = static::getDefaultValue();

        if (null !== $defaultValue) {
            $sqlDeclaration .= sprintf(' DEFAULT %s', $platform->quoteStringLiteral((string) $defaultValue));
        }

        return $sqlDeclaration;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (null !== $value && !in_array($value, $this->getValues())) {
            throw new \InvalidArgumentException(sprintf('Invalid value "%s" for ENUM "%s".', $value, $this->getName()));
        }

        return $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function getDefaultValue(): mixed
    {
        return null;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getValuesToString(): string
    {
        return implode(
            ', ',
            array_map(
                static fn (int|string $value): string => sprintf("'%s'", $value),
                $this->getValues(),
            ),
        );
    }

    public function buildDefaultSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $values = $this->getValuesToString();

        return match (true) {
            $platform instanceof SqlitePlatform => sprintf('TEXT CHECK(%s IN (%s))', $column['name'], $values),
            $platform instanceof PostgreSQLPlatform, $platform instanceof SQLServerPlatform => sprintf(
                'VARCHAR(255) CHECK(%s IN (%s))',
                $column['name'],
                $values,
            ),
            default => sprintf('ENUM(%s)', $values),
        };
    }

    abstract protected function getValues(): array;
}
