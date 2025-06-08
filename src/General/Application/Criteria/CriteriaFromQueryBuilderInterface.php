<?php

declare(strict_types=1);

namespace App\General\Application\Criteria;

use App\General\Domain\Criteria\Criteria;

interface CriteriaFromQueryBuilderInterface
{
    public function build(Criteria $criteria, QueryCriteriaDTO $dto): Criteria;
}
