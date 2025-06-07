<?php

declare(strict_types=1);

namespace App\Projections\Application\Query;

use App\Shared\Application\Bus\Query\QueryInterface;
use App\Shared\Application\Criteria\QueryCriteriaDTO;

final readonly class ProjectParticipantQuery implements QueryInterface
{
    public function __construct(
        public string $projectId,
        public QueryCriteriaDTO $criteria
    ) {
    }
}
