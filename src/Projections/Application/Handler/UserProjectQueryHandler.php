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
use App\Projections\Application\Query\UserProjectQuery;
use App\Projections\Domain\Repository\ProjectListProjectionRepositoryInterface;

/**
 * Class UserProjectQueryHandler
 *
 * @package App\Projections\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class UserProjectQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectListProjectionRepositoryInterface $repository,
        private CriteriaFromQueryBuilderInterface $criteriaBuilder,
        private PaginatorInterface $paginator
    ) {
    }

    public function __invoke(SymfonyUser $user, UserProjectQuery $query): Pagination
    {
        $criteria = new Criteria();

        $criteria->addOperand(new Operand('userId', OperatorEnum::Equal, $user->getUserIdentifier()))
            ->addOperand(new Operand('isInvolved', OperatorEnum::Equal, true))
            ->addOrder(new Order('finishDate'));

        $this->criteriaBuilder->build($criteria, $query->criteria);

        return $this->paginator->paginate($this->repository, $criteria);
    }
}
