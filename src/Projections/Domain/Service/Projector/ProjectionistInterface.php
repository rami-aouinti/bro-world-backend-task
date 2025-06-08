<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\Projections\Domain\DTO\ProjectionistResultDTO;

/**
 *
 */
interface ProjectionistInterface
{
    /**
     * @return ProjectionistResultDTO[]
     */
    public function projectAll(): array;
}
