<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;
use App\Projects\Domain\ValueObject\RequestChangeDate;

final class RequestChangeDateType extends DateTimeType
{
    private const TYPE_NAME = 'request_change_date';

    public function convertToPHPValue($value, AbstractPlatform $platform): RequestChangeDate
    {
        return new RequestChangeDate($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        return parent::convertToDatabaseValue($value->getPhpDateTime(), $platform);
    }

    public function getName(): string
    {
        return self::TYPE_NAME; // modify to match your constant name
    }
}
