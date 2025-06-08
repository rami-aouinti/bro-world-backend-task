<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

/**
 * Class ActiveProjectStatus
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ActiveProjectStatus extends ProjectStatus
{
    public function allowsModification(): bool
    {
        return true;
    }

    protected function getNextStatuses(): array
    {
        return [ClosedProjectStatus::class];
    }
}
