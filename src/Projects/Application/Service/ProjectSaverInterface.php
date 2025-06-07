<?php

declare(strict_types=1);

namespace App\Projects\Application\Service;

use App\Projects\Domain\Entity\Project;

interface ProjectSaverInterface
{
    public function save(Project $project, int $expectedVersion): int;
}
