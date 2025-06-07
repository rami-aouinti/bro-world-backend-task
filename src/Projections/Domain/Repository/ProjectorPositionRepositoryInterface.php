<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\Projections\Domain\Entity\ProjectorPosition;

interface ProjectorPositionRepositoryInterface
{
    public function findByProjectorName(string $name): ?ProjectorPosition;

    public function save(ProjectorPosition $position): void;
}
