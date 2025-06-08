<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\InvalidProjectRequestStatusTransitionException;
use LogicException;

use function get_class;
use function gettype;
use function sprintf;

/**
 * Class RequestStatus
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
abstract class RequestStatus extends Status
{
    public const int STATUS_PENDING = 0;
    public const int STATUS_CONFIRMED = 1;
    public const int STATUS_REJECTED = 2;

    protected function getNextStatuses(): array
    {
        return [];
    }

    public function allowsModification(): bool
    {
        return true;
    }

    public function getScalar(): int
    {
        if ($this instanceof PendingRequestStatus) {
            return self::STATUS_PENDING;
        }
        if ($this instanceof ConfirmedRequestStatus) {
            return self::STATUS_CONFIRMED;
        }
        if ($this instanceof RejectedRequestStatus) {
            return self::STATUS_REJECTED;
        }

        throw new LogicException(sprintf('Invalid type "%s" of project request status', gettype($this)));
    }

    public static function createFromScalar(?int $status): static
    {
        if (self::STATUS_PENDING === $status) {
            return new PendingRequestStatus();
        }
        if (self::STATUS_CONFIRMED === $status) {
            return new ConfirmedRequestStatus();
        }
        if (self::STATUS_REJECTED === $status) {
            return new RejectedRequestStatus();
        }

        throw new LogicException(sprintf('Invalid project request status "%s"', gettype($status)));
    }

    public function ensureCanBeChangedTo(self $status): void
    {
        if (!$this->canBeChangedTo($status)) {
            throw new InvalidProjectRequestStatusTransitionException(get_class($this), get_class($status));
        }
    }

    public function isPending(): bool
    {
        return self::STATUS_PENDING === $this->getScalar();
    }

    public function isConfirmed(): bool
    {
        return self::STATUS_CONFIRMED === $this->getScalar();
    }
}
