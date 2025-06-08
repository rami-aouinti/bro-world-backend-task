<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\General\Application\Bus\Query\QueryHandlerInterface;
use App\General\Application\Criteria\CriteriaFromQueryBuilderInterface;
use App\General\Application\Paginator\Pagination;
use App\General\Application\Paginator\PaginatorInterface;
use App\General\Domain\Criteria\Criteria;
use App\General\Domain\Criteria\Order;
use App\Projections\Application\Query\ProjectListQuery;
use App\Projections\Domain\Repository\ProjectListProjectionRepositoryInterface;

/**
 * Class ProjectListQueryHandler
 *
 * @package App\Projections\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectListQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectListProjectionRepositoryInterface $repository,
        private CriteriaFromQueryBuilderInterface $criteriaBuilder,
        private PaginatorInterface $paginator
    ) {
    }

    public function __invoke(ProjectListQuery $query): Pagination
    {
        $criteria = new Criteria();

        $criteria
            ->addOrder(new Order('finishDate'));

        $this->criteriaBuilder->build($criteria, $query->criteria);

        return $this->paginator->paginate($this->repository, $criteria);
    }
}
