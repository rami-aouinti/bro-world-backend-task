<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\General\Domain\Criteria\Criteria;
use App\Projections\Domain\Entity\ProjectRequestProjection;

/**
 * @method findAllByCriteria(Criteria $criteria): ProjectRequestProjection[]
 */
interface ProjectRequestProjectionRepositoryInterface extends PageableRepositoryInterface
{
    public function findById(string $id): ?ProjectRequestProjection;

    /**
     * @return ProjectRequestProjection[]
     */
    public function findAllByUserId(string $id): array;

    public function save(ProjectRequestProjection $projection): void;
}
