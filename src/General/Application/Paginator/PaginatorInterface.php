<?php

declare(strict_types=1);

namespace App\General\Application\Paginator;

use App\General\Domain\Criteria\Criteria;
use App\Projections\Domain\Repository\PageableRepositoryInterface;

interface PaginatorInterface
{
    public function paginate(PageableRepositoryInterface $repository, Criteria $criteria): Pagination;
}
