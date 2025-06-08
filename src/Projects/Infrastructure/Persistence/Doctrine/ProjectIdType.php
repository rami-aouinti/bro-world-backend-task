<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use App\Projects\Domain\ValueObject\ProjectId;

/**
 * Class ProjectIdType
 *
 * @package App\Projects\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectIdType extends StringType
{
    private const string TYPE_NAME = 'project_id';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return ProjectId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ProjectId
    {
        return new ProjectId($value);
    }

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->value;
    }

    public function getName(): string
    {
        return self::TYPE_NAME; // modify to match your constant name
    }
}
