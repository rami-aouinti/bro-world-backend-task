<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\InvalidTaskStatusTransitionException;
use App\Projects\Domain\Exception\TaskModificationIsNotAllowedException;
use LogicException;

use function get_class;
use function gettype;
use function sprintf;

/**
 * Class TaskStatus
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
abstract class TaskStatus extends Status
{
    public const int STATUS_CLOSED = 0;
    public const int STATUS_ACTIVE = 1;

    public function getScalar(): int
    {
        if ($this instanceof ClosedTaskStatus) {
            return self::STATUS_CLOSED;
        }
        if ($this instanceof ActiveTaskStatus) {
            return self::STATUS_ACTIVE;
        }

        throw new LogicException(sprintf('Invalid type "%s" of task status', gettype($this)));
    }

    public static function createFromScalar(?int $status): static
    {
        if (self::STATUS_CLOSED === $status) {
            return new ClosedTaskStatus();
        }
        if (self::STATUS_ACTIVE === $status) {
            return new ActiveTaskStatus();
        }

        throw new LogicException(sprintf('Invalid task status "%s"', gettype($status)));
    }

    public function ensureCanBeChangedTo(self $status): void
    {
        if (!$this->canBeChangedTo($status)) {
            throw new InvalidTaskStatusTransitionException(get_class($this), get_class($status));
        }
    }

    public function ensureAllowsModification(): void
    {
        if (!$this->allowsModification()) {
            throw new TaskModificationIsNotAllowedException(get_class($this));
        }
    }

    public function isClosed(): bool
    {
        return $this instanceof ClosedTaskStatus;
    }
}
