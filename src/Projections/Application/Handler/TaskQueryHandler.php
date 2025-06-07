<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\General\Infrastructure\ValueObject\SymfonyUser;
use App\Projections\Application\Query\TaskQuery;
use App\Projections\Domain\Entity\TaskProjection;
use App\Projections\Domain\Exception\InsufficientPermissionsException;
use App\Projections\Domain\Exception\ObjectDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Projections\Domain\Repository\TaskProjectionRepositoryInterface;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;

use function sprintf;

/**
 * Class TaskQueryHandler
 *
 * @package App\Projections\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TaskProjectionRepositoryInterface $repository,
        private ProjectProjectionRepositoryInterface $projectRepository,
    ) {
    }

    public function __invoke(SymfonyUser $user, TaskQuery $query): TaskProjection
    {
        $task = $this->repository->findById($query->id);
        if ($task === null) {
            throw new ObjectDoesNotExistException(sprintf('Task "%s" does not exist.', $query->id));
        }

        $project = $this->projectRepository->findByIdAndUserId($task->getProjectId(), $user->getUserIdentifier());
        if ($project === null) {
            throw new InsufficientPermissionsException(
                sprintf('Insufficient permissions to view the project "%s".', $task->getProjectId()));
        }

        return $task;
    }
}
