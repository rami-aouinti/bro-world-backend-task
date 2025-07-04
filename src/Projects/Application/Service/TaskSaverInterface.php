<?php

declare(strict_types=1);

namespace App\Projects\Application\Service;

use App\Projects\Domain\Entity\Task;

interface TaskSaverInterface
{
    public function save(Task $task, int $expectedVersion): int;
}
