<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\General\Domain\Criteria\Criteria;
use App\Projections\Domain\Entity\TaskListProjection;

/**
 * @method findAllByCriteria(Criteria $criteria): TaskListProjection[]
 */
interface TaskListProjectionRepositoryInterface extends PageableRepositoryInterface
{
    public function findById(string $id): ?TaskListProjection;

    /**
     * @return TaskListProjection[]
     */
    public function findAllByOwnerId(string $id): array;

    public function countByProjectAndOwnerId(string $projectId, string $ownerId): int;

    public function save(TaskListProjection $projection): void;

    public function delete(TaskListProjection $projection): void;
}
