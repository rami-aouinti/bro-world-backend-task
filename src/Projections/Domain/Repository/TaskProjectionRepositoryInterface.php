<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\Projections\Domain\Entity\TaskProjection;

interface TaskProjectionRepositoryInterface
{
    /**
     * @return TaskProjection[]
     */
    public function findAllByProjectId(string $id): array;

    public function findById(string $id): ?TaskProjection;

    public function save(TaskProjection $projection): void;

    public function delete(TaskProjection $projection): void;
}
