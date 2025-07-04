<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Persistence\Doctrine;

use App\General\Domain\ValueObject\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class UserIdType
 *
 * @package App\General\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserIdType extends StringType
{
    private const string TYPE_NAME = 'user_id';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return UserId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): UserId
    {
        return new UserId($value);
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
