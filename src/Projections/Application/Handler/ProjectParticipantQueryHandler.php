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
use App\Projections\Application\Query\ProjectParticipantQuery;
use App\Projections\Domain\Exception\InsufficientPermissionsException;
use App\Projections\Domain\Exception\ObjectDoesNotExistException;
use App\Projections\Domain\Repository\ProjectParticipantProjectionRepositoryInterface;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;

use function sprintf;

/**
 * Class ProjectParticipantQueryHandler
 *
 * @package App\Projections\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectParticipantQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectParticipantProjectionRepositoryInterface $repository,
        private ProjectProjectionRepositoryInterface $projectRepository,
        private CriteriaFromQueryBuilderInterface $criteriaBuilder,
        private PaginatorInterface $paginator
    ) {
    }

    public function __invoke(ProjectParticipantQuery $query, SymfonyUser $user): Pagination
    {
        $projectById = $this->projectRepository->findById($query->projectId);
        if ($projectById === null) {
            throw new ObjectDoesNotExistException(sprintf('Project "%s" does not exist.', $query->projectId));
        }

        $project = $this->projectRepository->findByIdAndUserId($query->projectId, $user->getUserIdentifier());
        if ($project === null) {
            throw new InsufficientPermissionsException(
                sprintf('Insufficient permissions to view the project "%s".', $query->projectId));
        }

        $criteria = new Criteria();

        $criteria->addOperand(new Operand('projectId', OperatorEnum::Equal, $query->projectId))
            ->addOrder(new Order('userEmail'))
            ->addOrder(new Order('userFirstname'))
            ->addOrder(new Order('userLastname'));

        $this->criteriaBuilder->build($criteria, $query->criteria);

        return $this->paginator->paginate($this->repository, $criteria);
    }
}
