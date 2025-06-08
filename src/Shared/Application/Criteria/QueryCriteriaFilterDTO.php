<?php

declare(strict_types=1);

namespace App\Shared\Application\Criteria;

/**
 * Class QueryCriteriaFilterDTO
 *
 * @package App\Shared\Application\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class QueryCriteriaFilterDTO
{
    public function __construct(
        public string $property,
        public string $operator,
        public mixed $value
    ) {
    }
}
