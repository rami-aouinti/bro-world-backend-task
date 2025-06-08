<?php

declare(strict_types=1);

namespace App\Projects\Application\Service;

use App\General\Application\OptimisticLock\OptimisticLockManagerInterface;
use App\General\Domain\Service\TransactionManagerInterface;
use App\Projects\Domain\Entity\Task;
use App\Projects\Domain\Repository\TaskRepositoryInterface;

/**
 * Class TaskSaver
 *
 * @package App\Projects\Application\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskSaver implements TaskSaverInterface
{
    public function __construct(
        private TaskRepositoryInterface $repository,
        private OptimisticLockManagerInterface $lockManager,
        private TransactionManagerInterface $transactionManager
    ) {
    }

    public function save(Task $task, int $expectedVersion): int
    {
        $newVersion = 0;

        $this->transactionManager->withTransaction(function () use ($task, $expectedVersion, &$newVersion) {
            $newVersion = $this->lockManager->lock($task, $expectedVersion);
            $this->repository->save($task);
        });

        return $newVersion;
    }
}
