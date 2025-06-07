<?php

declare(strict_types=1);

namespace App\Projections\Application\Query;

use App\Shared\Application\Bus\Query\QueryInterface;
use App\Shared\Application\Criteria\QueryCriteriaDTO;

/**
 * Class UserRequestQuery
 *
 * @package App\Projections\Application\Query
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserRequestQuery implements QueryInterface
{
    public function __construct(
        public QueryCriteriaDTO $criteria
    ) {
    }
}
