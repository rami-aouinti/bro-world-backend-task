<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;
use App\Projects\Domain\ValueObject\RequestStatus;

/**
 * Class RequestStatusType
 *
 * @package App\Projects\Infrastructure\Persistence\Doctrine
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class RequestStatusType extends IntegerType
{
    private const string TYPE_NAME = 'request_status';

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @throws ConversionException
     * @return RequestStatus
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): RequestStatus
    {
        return RequestStatus::createFromScalar(parent::convertToPHPValue($value, $platform));
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
