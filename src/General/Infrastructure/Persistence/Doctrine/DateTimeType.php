<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Persistence\Doctrine;

use App\General\Domain\ValueObject\DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType as SymfonyDateTimeType;

/**
 * Class DateTimeType
 *
 * @package App\General\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class DateTimeType extends SymfonyDateTimeType
{
    private const string TYPE_NAME = 'tm_datetime';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return DateTime
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): DateTime
    {
        return new DateTime($value);
    }

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @throws ConversionException
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        return parent::convertToDatabaseValue($value->getPhpDateTime(), $platform);
    }

    public function getName(): string
    {
        return self::TYPE_NAME; // modify to match your constant name
    }
}
