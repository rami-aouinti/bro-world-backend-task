<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

/**
 * Class ClosedProjectStatus
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ClosedProjectStatus extends ProjectStatus
{
    public function allowsModification(): bool
    {
        return false;
    }

    protected function getNextStatuses(): array
    {
        return [ActiveProjectStatus::class];
    }
}
