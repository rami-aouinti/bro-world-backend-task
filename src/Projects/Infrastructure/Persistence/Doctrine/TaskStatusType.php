<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;
use App\Projects\Domain\ValueObject\TaskStatus;

/**
 * Class TaskStatusType
 *
 * @package App\Projects\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskStatusType extends IntegerType
{
    private const string TYPE_NAME = 'task_status';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @throws ConversionException
     * @return TaskStatus
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): TaskStatus
    {
        return TaskStatus::createFromScalar(parent::convertToPHPValue($value, $platform));
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
