<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Criteria;

use App\General\Application\Criteria\QueryCriteriaDTO;

/**
 *
 */
interface QueryCriteriaFromRequestConverterInterface
{
    public function convert(RequestCriteriaDTO $dto): QueryCriteriaDTO;
}
