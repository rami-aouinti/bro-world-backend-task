<?php

declare(strict_types=1);

namespace App\Projections\Domain\Entity;

use App\General\Domain\ValueObject\DateTime;

/**
 * Class ProjectorPosition
 *
 * @package App\Projections\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectorPosition
{
    public function __construct(
        private readonly string $projectorName,
        private ?DateTime $position = null,
        private bool $isBroken = false
    ) {
    }

    public function adjustPosition(?DateTime $position): void
    {
        $this->position = $position;
    }

    public function markAsBroken(): void
    {
        $this->isBroken = true;
    }

    public function isBroken(): bool
    {
        return $this->isBroken;
    }

    public function getPosition(): ?DateTime
    {
        return $this->position;
    }
}
