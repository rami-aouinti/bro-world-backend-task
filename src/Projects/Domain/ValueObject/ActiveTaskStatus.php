<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

/**
 * Class ActiveTaskStatus
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ActiveTaskStatus extends TaskStatus
{
    public function allowsModification(): bool
    {
        return true;
    }

    protected function getNextStatuses(): array
    {
        return [ClosedTaskStatus::class];
    }
}
