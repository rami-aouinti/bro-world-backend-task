<?php

declare(strict_types=1);

namespace App\Projects\Application\Service;

use App\Projects\Domain\Entity\Project;
use App\Projects\Domain\ValueObject\ProjectId;

interface ProjectFinderInterface
{
    public function find(ProjectId $id): Project;
}
