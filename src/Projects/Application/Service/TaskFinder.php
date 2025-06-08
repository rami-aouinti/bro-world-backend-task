<?php

declare(strict_types=1);

namespace App\Projects\Application\Service;

use App\Projects\Domain\Entity\Task;
use App\Projects\Domain\Exception\TaskDoesNotExistException;
use App\Projects\Domain\Repository\TaskRepositoryInterface;
use App\Projects\Domain\ValueObject\TaskId;

/**
 * Class TaskFinder
 *
 * @package App\Projects\Application\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskFinder implements TaskFinderInterface
{
    public function __construct(private TaskRepositoryInterface $repository)
    {
    }

    public function find(TaskId $id): Task
    {
        $task = $this->repository->findById($id);
        if ($task === null) {
            throw new TaskDoesNotExistException($id->value);
        }

        return $task;
    }
}
