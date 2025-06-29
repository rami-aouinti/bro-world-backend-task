<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use App\Projects\Domain\ValueObject\UserEmail;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;


/**
 * Class UserEmailType
 *
 * @package App\Projects\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserEmailType extends StringType
{
    private const TYPE_NAME = 'user_email';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return UserEmail
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): UserEmail
    {
        return new UserEmail($value);
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
