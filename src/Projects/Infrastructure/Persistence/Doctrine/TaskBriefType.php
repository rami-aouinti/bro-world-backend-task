<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use App\Projects\Domain\ValueObject\TaskBrief;

/**
 * Class TaskBriefType
 *
 * @package App\Projects\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskBriefType extends StringType
{
    private const string TYPE_NAME = 'task_brief';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return TaskBrief
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): TaskBrief
    {
        return new TaskBrief($value);
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
