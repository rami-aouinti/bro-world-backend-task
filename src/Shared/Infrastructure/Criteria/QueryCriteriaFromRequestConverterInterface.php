<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Criteria;

use App\Shared\Application\Criteria\QueryCriteriaDTO;

/**
 *
 */
interface QueryCriteriaFromRequestConverterInterface
{
    public function convert(RequestCriteriaDTO $dto): QueryCriteriaDTO;
}
