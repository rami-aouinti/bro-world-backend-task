<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;
use App\Projects\Domain\ValueObject\ProjectStatus;

/**
 * Class ProjectStatusType
 *
 * @package App\Projects\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectStatusType extends IntegerType
{
    private const string TYPE_NAME = 'project_status';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @throws ConversionException
     * @return ProjectStatus
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ProjectStatus
    {
        return ProjectStatus::createFromScalar(parent::convertToPHPValue($value, $platform));
    }

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return int
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        return $value->getScalar();
    }

    public function getName(): string
    {
        return self::TYPE_NAME; // modify to match your constant name
    }
}
