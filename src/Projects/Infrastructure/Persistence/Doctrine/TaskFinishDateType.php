<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
use App\Projects\Domain\ValueObject\TaskFinishDate;

/**
 * Class TaskFinishDateType
 *
 * @package App\Projects\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskFinishDateType extends DateTimeType
{
    private const string TYPE_NAME = 'task_finish_date';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return TaskFinishDate
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): TaskFinishDate
    {
        return new TaskFinishDate($value);
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
