<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\General\Domain\Equatable;

use function get_class;
use function in_array;

/**
 * Class Status
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
abstract class Status implements Equatable
{
    abstract public function getScalar(): int;

    abstract public static function createFromScalar(int $status): static;

    abstract protected function getNextStatuses(): array;

    abstract public function allowsModification(): bool;

    public function canBeChangedTo(self $status): bool
    {
        return in_array(get_class($status), $this->getNextStatuses(), true);
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof static;
    }
}
