<?php

declare(strict_types=1);

namespace App\Projections\Application\Query;

use App\Shared\Application\Bus\Query\QueryInterface;
use App\Shared\Application\Criteria\QueryCriteriaDTO;

final class UserRequestQuery implements QueryInterface
{
    public function __construct(
        public QueryCriteriaDTO $criteria
    ) {
    }
}
