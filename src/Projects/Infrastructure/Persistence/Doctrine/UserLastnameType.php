<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use App\Projects\Domain\ValueObject\UserLastname;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class UserLastnameType
 *
 * @package App\Projects\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserLastnameType extends StringType
{
    private const string TYPE_NAME = 'user_lastname';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return UserLastname
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): UserLastname
    {
        return new UserLastname($value);
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
