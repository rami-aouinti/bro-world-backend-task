<?php

declare(strict_types=1);

namespace App\General\Domain\Criteria;

/**
 * Class Order
 *
 * @package App\General\Domain\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class Order
{
    public function __construct(
        public string $property,
        public bool $isAsc = true
    ) {
    }
}
