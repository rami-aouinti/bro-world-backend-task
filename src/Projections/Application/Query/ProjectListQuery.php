<?php

declare(strict_types=1);

namespace App\Projections\Application\Query;

use App\General\Application\Bus\Query\QueryInterface;
use App\General\Application\Criteria\QueryCriteriaDTO;

/**
 * Class ProjectListQuery
 *
 * @package App\Projections\Application\Query
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectListQuery implements QueryInterface
{
    public function __construct(
        public QueryCriteriaDTO $criteria
    ) {
    }
}
