<?php

declare(strict_types=1);

namespace App\Shared\Application\Criteria;

final readonly class QueryCriteriaFilterDTO
{
    public function __construct(
        public string $property,
        public string $operator,
        public mixed $value
    ) {
    }
}
