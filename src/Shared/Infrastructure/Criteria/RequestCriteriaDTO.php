<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Criteria;

/**
 * Class RequestCriteriaDTO
 *
 * @package App\Shared\Infrastructure\Criteria
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
