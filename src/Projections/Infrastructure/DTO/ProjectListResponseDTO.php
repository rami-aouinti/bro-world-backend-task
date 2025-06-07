<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\DTO;

use OpenApi\Attributes as OA;
use App\Projections\Domain\DTO\ProjectListMemento;
use App\Projections\Domain\Entity\ProjectListProjection;

/**
 * Class ProjectListResponseDTO
 *
 * @package App\Projections\Infrastructure\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectListResponseDTO
{
    public string $id;

    public string $name;

    public string $finishDate;

    public string $ownerId;

    public string $ownerFullName;

    public int $status;

    public int $tasksCount;

    public int $participantsCount;

    public int $pendingRequestsCount;

    public bool $isOwner;

    public bool $isInvolved;

    public ?int $lastRequestStatus;

    public function __construct(ProjectListMemento $memento)
    {
        $this->id = $memento->id;
        $this->name = $memento->name;
        $this->finishDate = $memento->finishDate;
        $this->ownerId = $memento->ownerId;
        $this->ownerFullName = $memento->ownerFullName;
        $this->status = $memento->status;
        $this->tasksCount = $memento->tasksCount;
        $this->participantsCount = $memento->participantsCount;
        $this->pendingRequestsCount = $memento->pendingRequestsCount;
        $this->isOwner = $memento->isOwner;
        $this->isInvolved = $memento->isInvolved;
        $this->lastRequestStatus = $memento->lastRequestStatus;
    }

    /**
     * @param ProjectListProjection[] $projections
     *
     * @return self[]
     */
    public static function createList(array $projections): array
    {
        $result = [];

        foreach ($projections as $projection) {
            $result[] = new self($projection->createMemento());
        }

        return $result;
    }
}
