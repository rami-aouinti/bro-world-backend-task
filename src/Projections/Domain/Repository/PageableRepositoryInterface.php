<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\General\Domain\Criteria\Criteria;

/**
 *
 */
interface PageableRepositoryInterface
{
    public function findAllByCriteria(Criteria $criteria): array;

    public function findCountByCriteria(Criteria $criteria): int;
}
