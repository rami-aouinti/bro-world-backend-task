<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Criteria;

/**
 * Class RequestCriteriaDTO
 *
 * @package App\General\Infrastructure\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class RequestCriteriaDTO
{
    public function __construct(
        public array $filters = [],
        public array $orders = [],
        public ?int $page = null
    ) {
    }
}
