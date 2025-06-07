<?php

declare(strict_types=1);

namespace App\Projections\Domain\Entity;

use App\Projections\Domain\DTO\TaskLinkMemento;
use App\Shared\Domain\Hashable;

/**
 * Class TaskLinkProjection
 *
 * @package App\Projections\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskLinkProjection implements Hashable
{
    public function __construct(
        private readonly string $taskId,
        private readonly string $linkedTaskId,
        private string $linkedTaskName,
        private int $linkedTaskStatus
    ) {
    }

    public static function hash(string $taskId, string $linkedTaskId): string
    {
        return $taskId.$linkedTaskId;
    }

    public function getHash(): string
    {
        return self::hash($this->taskId, $this->linkedTaskId);
    }

    public static function create(
        string $taskId,
        string $linkedTaskId,
        string $linkedTaskName,
        int $linkedTaskStatus
    ): self {
        return new self(
            $taskId,
            $linkedTaskId,
            $linkedTaskName,
            $linkedTaskStatus
        );
    }

    public function changeLinkedTaskInformation(string $name): void
    {
        $this->linkedTaskName = $name;
    }

    public function changeLinkedTaskStatus(string $status): void
    {
        $this->linkedTaskStatus = (int) $status;
    }

    public function createMemento(): TaskLinkMemento
    {
        return new TaskLinkMemento(
            $this->taskId,
            $this->linkedTaskId,
            $this->linkedTaskName,
            $this->linkedTaskStatus
        );
    }

    public function isLinkedTask(string $taskId): bool
    {
        return $this->linkedTaskId === $taskId;
    }
}
