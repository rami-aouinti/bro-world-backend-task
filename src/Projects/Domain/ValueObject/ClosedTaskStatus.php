<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

/**
 * Class ClosedTaskStatus
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ClosedTaskStatus extends TaskStatus
{
    public function allowsModification(): bool
    {
        return false;
    }

    protected function getNextStatuses(): array
    {
        return [ActiveTaskStatus::class];
    }
}
