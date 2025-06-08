<?php

declare(strict_types=1);

namespace App\Projects\Application\Service;

use App\Projects\Domain\Entity\Project;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Shared\Application\OptimisticLock\OptimisticLockManagerInterface;
use App\Shared\Domain\Service\TransactionManagerInterface;

/**
 * Class ProjectSaver
 *
 * @package App\Projects\Application\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectSaver implements ProjectSaverInterface
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
        private OptimisticLockManagerInterface $lockManager,
        private TransactionManagerInterface $transactionManager
    ) {
    }

    public function save(Project $project, int $expectedVersion): int
    {
        $newVersion = 0;

        $this->transactionManager->withTransaction(function () use ($project, $expectedVersion, &$newVersion) {
            $newVersion = $this->lockManager->lock($project, $expectedVersion);
            $this->repository->save($project);
        });

        return $newVersion;
    }
}
