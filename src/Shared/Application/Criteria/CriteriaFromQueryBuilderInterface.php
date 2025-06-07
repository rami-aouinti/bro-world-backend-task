<?php

declare(strict_types=1);

namespace App\Shared\Application\Criteria;

use App\Shared\Domain\Criteria\Criteria;

interface CriteriaFromQueryBuilderInterface
{
    public function build(Criteria $criteria, QueryCriteriaDTO $dto): Criteria;
}
