<?php

declare(strict_types=1);

namespace App\Projections\Domain\DTO;

/**
 * Class TaskListMemento
 *
 * @package App\Projections\Domain\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskListMemento
{
    public function __construct(
        public string $id,
        public string $name,
        public string $startDate,
        public string $finishDate,
        public string $ownerId,
        public string $ownerFullName,
        public int $status,
        public int $linksCount
    ) {
    }
}
