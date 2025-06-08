<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\General\Domain\Criteria\Criteria;
use App\Projections\Domain\Entity\TaskLinkProjection;

/**
 * @method findAllByCriteria(Criteria $criteria): TaskLinkProjection[]
 */
interface TaskLinkProjectionRepositoryInterface extends PageableRepositoryInterface
{
    /**
     * @return TaskLinkProjection[]
     */
    public function findAllByLinkedTaskId(string $id): array;

    public function findByTaskAndLinkedTaskId(string $taskId, string $linkedTaskId): ?TaskLinkProjection;

    public function save(TaskLinkProjection $projection): void;

    public function delete(TaskLinkProjection $projection): void;
}
