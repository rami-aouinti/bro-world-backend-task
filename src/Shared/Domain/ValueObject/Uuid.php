<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Equatable;
use App\Shared\Domain\Exception\InvalidArgumentException;
use Stringable;

use function sprintf;

/**
 * Class Uuid
 *
 * @package App\Shared\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class Uuid implements Stringable, Equatable
{
    public function __construct(public readonly string $value)
    {
        $this->ensureIsValidUuid($this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof static && $this->value === $other->value;
    }

    private function ensureIsValidUuid(string $value): void
    {
        $pattern = '/\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}\z/Dms';
        if (!preg_match($pattern, $value)) {
            throw new InvalidArgumentException(sprintf('Invalid uuid "%s"', $value));
        }
    }
}
