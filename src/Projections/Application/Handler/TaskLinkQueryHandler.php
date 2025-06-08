<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\General\Application\Bus\Query\QueryHandlerInterface;
use App\General\Application\Criteria\CriteriaFromQueryBuilderInterface;
use App\General\Application\Paginator\Pagination;
use App\General\Application\Paginator\PaginatorInterface;
use App\General\Domain\Criteria\Criteria;
use App\General\Domain\Criteria\Operand;
use App\General\Domain\Criteria\OperatorEnum;
use App\General\Domain\Criteria\Order;
use App\General\Infrastructure\ValueObject\SymfonyUser;
use App\Projections\Application\Query\TaskLinkQuery;
use App\Projections\Domain\Exception\InsufficientPermissionsException;
use App\Projections\Domain\Exception\ObjectDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Projections\Domain\Repository\TaskLinkProjectionRepositoryInterface;
use App\Projections\Domain\Repository\TaskProjectionRepositoryInterface;

use function sprintf;

/**
 * Class TaskLinkQueryHandler
 *
 * @package App\Projections\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskLinkQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TaskLinkProjectionRepositoryInterface $repository,
        private TaskProjectionRepositoryInterface $taskRepository,
        private ProjectProjectionRepositoryInterface $projectRepository,
        private CriteriaFromQueryBuilderInterface $criteriaBuilder,
        private PaginatorInterface $paginator
    ) {
    }

    public function __invoke(SymfonyUser $user, TaskLinkQuery $query): Pagination
    {
        $task = $this->taskRepository->findById($query->taskId);
        if ($task === null) {
            throw new ObjectDoesNotExistException(sprintf('Task "%s" does not exist.', $query->taskId));
        }

        $project = $this->projectRepository->findByIdAndUserId($task->getProjectId(), $user->getUserIdentifier());
        if ($project === null) {
            throw new InsufficientPermissionsException(
                sprintf('Insufficient permissions to view the project "%s".', $task->getProjectId()));
        }

        $criteria = new Criteria();

        $criteria->addOperand(new Operand('taskId', OperatorEnum::Equal, $query->taskId))
            ->addOrder(new Order('linkedTaskName'));

        $this->criteriaBuilder->build($criteria, $query->criteria);

        return $this->paginator->paginate($this->repository, $criteria);
    }
}
