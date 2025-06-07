<?php

declare(strict_types=1);

namespace App\Projections\Domain\Entity;

use App\Projections\Domain\DTO\TaskListMemento;
use App\Shared\Domain\Hashable;
use App\Shared\Domain\ValueObject\DateTime;

/**
 * Class TaskListProjection
 *
 * @package App\Projections\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskListProjection implements Hashable
{
    private int $linksCount = 0;

    public function __construct(
        private readonly string $id,
        private string $name,
        private DateTime $startDate,
        private DateTime $finishDate,
        private readonly string $ownerId,
        private string $ownerFullName,
        private int $status,
        private readonly string $projectId
    ) {
    }

    public function getHash(): string
    {
        return $this->id;
    }

    public static function create(
        string $id,
        string $name,
        string $startDate,
        string $finishDate,
        string $ownerId,
        string $ownerFullName,
        string $status,
        string $projectId
    ): self {
        return new self(
            $id,
            $name,
            new DateTime($startDate),
            new DateTime($finishDate),
            $ownerId,
            $ownerFullName,
            (int) $status,
            $projectId
        );
    }

    public function changeInformation(string $name, string $startDate, string $finishDate): void
    {
        $this->name = $name;
        $this->startDate = new DateTime($startDate);
        $this->finishDate = new DateTime($finishDate);
    }

    public function changeStatus(string $status): void
    {
        $this->status = (int) $status;
    }

    public function changeOwnerInformation(string $fullName): void
    {
        $this->ownerFullName = $fullName;
    }

    public function createLink(): void
    {
        ++$this->linksCount;
    }

    public function deleteLink(): void
    {
        --$this->linksCount;
    }

    public function createMemento(): TaskListMemento
    {
        return new TaskListMemento(
            $this->id,
            $this->name,
            $this->startDate->getValue(),
            $this->finishDate->getValue(),
            $this->ownerId,
            $this->ownerFullName,
            $this->status,
            $this->linksCount
        );
    }

    public function isUserOwner(string $ownerId): bool
    {
        return $this->ownerId == $ownerId;
    }
}
