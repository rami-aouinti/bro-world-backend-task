<?php

declare(strict_types=1);

namespace App\General\Application\Paginator;

use App\General\Domain\Criteria\Criteria;
use App\Projections\Domain\Repository\PageableRepositoryInterface;

/**
 * Class Paginator
 *
 * @package App\General\Application\Paginator
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
