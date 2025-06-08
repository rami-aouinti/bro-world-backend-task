<?php

declare(strict_types=1);

namespace App\Projections\Domain\DTO;

/**
 * Class ProjectionistResultDTO
 *
 * @package App\Projections\Domain\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectionistResultDTO
{
    public function __construct(
        public string $projector,
        public int $eventCount,
        public bool $isBroken = false
    ) {
    }
}
