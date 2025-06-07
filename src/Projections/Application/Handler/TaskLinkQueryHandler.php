<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\Projections\Application\Query\TaskLinkQuery;
use App\Projections\Application\Service\CurrentUserExtractorInterface;
use App\Projections\Domain\Exception\InsufficientPermissionsException;
use App\Projections\Domain\Exception\ObjectDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Projections\Domain\Repository\TaskLinkProjectionRepositoryInterface;
use App\Projections\Domain\Repository\TaskProjectionRepositoryInterface;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Shared\Application\Criteria\CriteriaFromQueryBuilderInterface;
use App\Shared\Application\Paginator\Pagination;
use App\Shared\Application\Paginator\PaginatorInterface;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Operand;
use App\Shared\Domain\Criteria\OperatorEnum;
use App\Shared\Domain\Criteria\Order;

final readonly class TaskLinkQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TaskLinkProjectionRepositoryInterface $repository,
        private TaskProjectionRepositoryInterface $taskRepository,
        private ProjectProjectionRepositoryInterface $projectRepository,
        private CriteriaFromQueryBuilderInterface $criteriaBuilder,
        private CurrentUserExtractorInterface $userExtractor,
        private PaginatorInterface $paginator
    ) {
    }

    public function __invoke(TaskLinkQuery $query): Pagination
    {
        $user = $this->userExtractor->extract();

        $task = $this->taskRepository->findById($query->taskId);
        if (null === $task) {
            throw new ObjectDoesNotExistException(sprintf('Task "%s" does not exist.', $query->taskId));
        }

        $project = $this->projectRepository->findByIdAndUserId($task->getProjectId(), $user->getId());
        if (null === $project) {
            throw new InsufficientPermissionsException(sprintf('Insufficient permissions to view the project "%s".', $task->getProjectId()));
        }

        $criteria = new Criteria();

        $criteria->addOperand(new Operand('taskId', OperatorEnum::Equal, $query->taskId))
            ->addOrder(new Order('linkedTaskName'));

        $this->criteriaBuilder->build($criteria, $query->criteria);

        return $this->paginator->paginate($this->repository, $criteria);
    }
}
