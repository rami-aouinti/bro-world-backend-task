<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\Projections\Application\Query\UserRequestQuery;
use App\Projections\Application\Service\CurrentUserExtractorInterface;
use App\Projections\Domain\Repository\UserRequestProjectionRepositoryInterface;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Shared\Application\Criteria\CriteriaFromQueryBuilderInterface;
use App\Shared\Application\Paginator\Pagination;
use App\Shared\Application\Paginator\PaginatorInterface;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Operand;
use App\Shared\Domain\Criteria\OperatorEnum;
use App\Shared\Domain\Criteria\Order;

final readonly class UserRequestQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRequestProjectionRepositoryInterface $repository,
        private CriteriaFromQueryBuilderInterface $criteriaBuilder,
        private CurrentUserExtractorInterface $userExtractor,
        private PaginatorInterface $paginator
    ) {
    }

    public function __invoke(UserRequestQuery $query): Pagination
    {
        $user = $this->userExtractor->extract();

        $criteria = new Criteria();

        $criteria->addOperand(new Operand('userId', OperatorEnum::Equal, $user->getId()))
            ->addOrder(new Order('changeDate'));

        $this->criteriaBuilder->build($criteria, $query->criteria);

        return $this->paginator->paginate($this->repository, $criteria);
    }
}
