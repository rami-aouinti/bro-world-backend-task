<?php

declare(strict_types=1);

namespace App\General\Domain\ValueObject;

use App\General\Domain\Equatable;
use App\General\Domain\Exception\InvalidArgumentException;
use DateTimeImmutable;
use Exception;
use LogicException;
use Stringable;

use function sprintf;

/**
 * Class DateTime
 *
 * @package App\General\Infrastructure\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class DateTime implements Stringable, Equatable
{
    // ATOM with microseconds
    public const string DEFAULT_FORMAT = 'Y-m-d\TH:i:s.uP';

    private DateTimeImmutable $dateTime;

    public function __construct(string $value = null)
    {
        if ($value) {
            try {
                $this->dateTime = new DateTimeImmutable($value);
            } catch (Exception) {
                throw new InvalidArgumentException(sprintf('Invalid datetime value "%s"', $value));
            }
        } else {
            $dateTime = DateTimeImmutable::createFromFormat(
                'U.u',
                sprintf('%.f', microtime(true))
            );
            if ($dateTime === false) {
                throw new LogicException('Cannot create DateTimeImmutable from format');
            }
            $this->dateTime = $dateTime;
        }
    }

    public function getValue(): string
    {
        return $this->dateTime->format(self::DEFAULT_FORMAT);
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function isGreaterThan(self $other): bool
    {
        return $this->dateTime > $other->dateTime;
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof static && $this->getValue() === $other->getValue();
    }

    public function getPhpDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
