<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\Projections\Application\Query\TaskQuery;
use App\Projections\Application\Service\CurrentUserExtractorInterface;
use App\Projections\Domain\Entity\TaskProjection;
use App\Projections\Domain\Exception\InsufficientPermissionsException;
use App\Projections\Domain\Exception\ObjectDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Projections\Domain\Repository\TaskProjectionRepositoryInterface;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;

final readonly class TaskQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TaskProjectionRepositoryInterface $repository,
        private ProjectProjectionRepositoryInterface $projectRepository,
        private CurrentUserExtractorInterface $userExtractor
    ) {
    }

    public function __invoke(TaskQuery $query): TaskProjection
    {
        $user = $this->userExtractor->extract();

        $task = $this->repository->findById($query->id);
        if (null === $task) {
            throw new ObjectDoesNotExistException(sprintf('Task "%s" does not exist.', $query->id));
        }

        $project = $this->projectRepository->findByIdAndUserId($task->getProjectId(), $user->getId());
        if (null === $project) {
            throw new InsufficientPermissionsException(sprintf('Insufficient permissions to view the project "%s".', $task->getProjectId()));
        }

        return $task;
    }
}
