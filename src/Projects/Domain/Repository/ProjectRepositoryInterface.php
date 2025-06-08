<?php

declare(strict_types=1);

namespace App\Projects\Domain\Repository;

use App\Projects\Domain\Entity\Project;
use App\Projects\Domain\ValueObject\ProjectId;

/**
 *
 */
interface ProjectRepositoryInterface
{
    public function findById(ProjectId $id): ?Project;

    public function save(Project $project): void;
}
