<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\General\Domain\Criteria\Criteria;
use App\Projections\Domain\Entity\ProjectListProjection;

/**
 * @method findAllByCriteria(Criteria $criteria): ProjectListProjection[]
 */
interface ProjectListProjectionRepositoryInterface extends PageableRepositoryInterface
{
    /**
     * @return ProjectListProjection[]
     */
    public function findAllById(string $id): array;

    /**
     * @return ProjectListProjection[]
     */
    public function findAllByOwnerId(string $id): array;

    /**
     * @return ProjectListProjection[]
     */
    public function findAllOwnersProjects(): array;

    public function save(ProjectListProjection $projection): void;

    public function delete(ProjectListProjection $projection): void;
}
