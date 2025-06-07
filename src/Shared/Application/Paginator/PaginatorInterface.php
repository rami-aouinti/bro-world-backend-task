<?php

declare(strict_types=1);

namespace App\Shared\Application\Paginator;

use App\Projections\Domain\Repository\PageableRepositoryInterface;
use App\Shared\Domain\Criteria\Criteria;

interface PaginatorInterface
{
    public function paginate(PageableRepositoryInterface $repository, Criteria $criteria): Pagination;
}
