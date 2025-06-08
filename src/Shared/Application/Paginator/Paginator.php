<?php

declare(strict_types=1);

namespace App\Shared\Application\Paginator;

use App\Projections\Domain\Repository\PageableRepositoryInterface;
use App\Shared\Domain\Criteria\Criteria;

/**
 * Class Paginator
 *
 * @package App\Shared\Application\Paginator
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class Paginator implements PaginatorInterface
{
    public function paginate(PageableRepositoryInterface $repository, Criteria $criteria): Pagination
    {
        return new Pagination(
            $repository->findAllByCriteria($criteria),
            $repository->findCountByCriteria($criteria),
            $criteria->getOffset(),
            $criteria->getLimit()
        );
    }
}
