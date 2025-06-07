<?php

declare(strict_types=1);

namespace App\Projects\Application\Service;

use App\Projects\Domain\Entity\Task;
use App\Projects\Domain\ValueObject\TaskId;

interface TaskFinderInterface
{
    public function find(TaskId $id): Task;
}
