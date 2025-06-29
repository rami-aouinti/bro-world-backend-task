<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use App\Projects\Domain\ValueObject\UserFirstname;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class UserFirstnameType
 *
 * @package App\Projects\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserFirstnameType extends StringType
{
    private const TYPE_NAME = 'user_firstname';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return UserFirstname
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): UserFirstname
    {
        return new UserFirstname($value);
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
