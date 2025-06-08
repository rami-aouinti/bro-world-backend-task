<?php

declare(strict_types=1);

namespace App\Projects\Domain\Repository;

use App\Projects\Domain\Entity\Task;
use App\Projects\Domain\ValueObject\TaskId;

/**
 *
 */
interface TaskRepositoryInterface
{
    public function findById(TaskId $id): ?Task;

    public function save(Task $task): void;
}
