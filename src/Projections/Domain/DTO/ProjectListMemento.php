<?php

declare(strict_types=1);

namespace App\Projections\Domain\DTO;

/**
 * Class ProjectListMemento
 *
 * @package App\Projections\Domain\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectListMemento
{
    public function __construct(
        public string $id,
        public string $name,
        public string $finishDate,
        public string $ownerId,
        public string $ownerFullName,
        public int $status,
        public int $tasksCount,
        public int $participantsCount,
        public int $pendingRequestsCount,
        public bool $isOwner,
        public bool $isInvolved,
        public ?int $lastRequestStatus
    ) {
    }
}
