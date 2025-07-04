<?php

declare(strict_types=1);

namespace App\General\Application\Criteria;

/**
 * Class QueryCriteriaDTO
 *
 * @package App\General\Application\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class QueryCriteriaDTO
{
    /**
     * @param QueryCriteriaFilterDTO[] $filters
     */
    public function __construct(
        public array $filters,
        public array $orders,
        public ?int $offset,
        public ?int $limit
    ) {
    }
}
