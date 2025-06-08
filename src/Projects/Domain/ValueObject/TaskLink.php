<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\General\Domain\Equatable;
use App\General\Domain\Hashable;
use App\Projects\Domain\Exception\TasksOfTaskLinkAreEqualException;

/**
 * Class TaskLink
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskLink implements Equatable, Hashable
{
    public function __construct(
        public TaskId $taskId,
        public TaskId $linkedTaskId
    ) {
        $this->ensureTasksAreNotEqual();
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->taskId->equals($this->taskId)
            && $other->linkedTaskId->equals($this->linkedTaskId);
    }

    public function getHash(): string
    {
        return $this->linkedTaskId->value;
    }

    private function ensureTasksAreNotEqual(): void
    {
        if ($this->taskId->equals($this->linkedTaskId)) {
            throw new TasksOfTaskLinkAreEqualException($this->taskId->value);
        }
    }
}
