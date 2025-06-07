<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\Projections\Application\Query\ProjectRequestQuery;
use App\Projections\Application\Service\CurrentUserExtractorInterface;
use App\Projections\Domain\Exception\InsufficientPermissionsException;
use App\Projections\Domain\Exception\ObjectDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Projections\Domain\Repository\ProjectRequestProjectionRepositoryInterface;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Shared\Application\Criteria\CriteriaFromQueryBuilderInterface;
use App\Shared\Application\Paginator\Pagination;
use App\Shared\Application\Paginator\PaginatorInterface;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Operand;
use App\Shared\Domain\Criteria\OperatorEnum;
use App\Shared\Domain\Criteria\Order;

final readonly class ProjectRequestQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectRequestProjectionRepositoryInterface $repository,
        private ProjectProjectionRepositoryInterface $projectRepository,
        private CriteriaFromQueryBuilderInterface $criteriaBuilder,
        private CurrentUserExtractorInterface $userExtractor,
        private PaginatorInterface $paginator
    ) {
    }

    public function __invoke(ProjectRequestQuery $query): Pagination
    {
        $user = $this->userExtractor->extract();

        $project = $this->projectRepository->findById($query->projectId);
        if (null === $project) {
            throw new ObjectDoesNotExistException(sprintf('Project "%s" does not exist.', $query->projectId));
        }

        if (!$project->isUserOwner($user->getId())) {
            throw new InsufficientPermissionsException(sprintf('Insufficient permissions to view the project "%s".', $query->projectId));
        }

        $criteria = new Criteria();

        $criteria->addOperand(new Operand('projectId', OperatorEnum::Equal, $query->projectId))
            ->addOrder(new Order('changeDate'));

        $this->criteriaBuilder->build($criteria, $query->criteria);

        return $this->paginator->paginate($this->repository, $criteria);
    }
}
