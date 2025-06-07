<?php

declare(strict_types=1);

namespace App\Projects\Domain\Entity;

use App\Projects\Domain\ValueObject\PendingRequestStatus;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\RequestChangeDate;
use App\Projects\Domain\ValueObject\RequestId;
use App\Projects\Domain\ValueObject\RequestStatus;
use App\Shared\Domain\Equatable;
use App\Shared\Domain\Hashable;
use App\Shared\Domain\ValueObject\UserId;

/**
 * Class Request
 *
 * @package App\Projects\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class Request implements Equatable, Hashable
{
    public function __construct(
        private readonly RequestId $id,
        private readonly ProjectId $projectId,
        private readonly UserId $userId,
        private RequestStatus $status,
        private RequestChangeDate $changeDate
    ) {
    }

    public static function create(RequestId $id, ProjectId $projectId, UserId $userId): self
    {
        $status = new PendingRequestStatus();
        $changeDate = new RequestChangeDate();

        return new Request($id, $projectId, $userId, $status, $changeDate);
    }

    public function changeStatus(RequestStatus $status): void
    {
        $this->status->ensureCanBeChangedTo($status);
        $this->status = $status;
        $this->changeDate = new RequestChangeDate();
    }

    public function isPendingForUser(UserId $userId): bool
    {
        return $this->status->isPending() && $this->userId->equals($userId);
    }

    public function getId(): RequestId
    {
        return $this->id;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }

    public function getStatus(): RequestStatus
    {
        return $this->status;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getChangeDate(): RequestChangeDate
    {
        return $this->changeDate;
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->id->equals($this->id)
            && $other->userId->equals($this->userId)
            && $other->status->equals($this->status)
            && $other->changeDate->equals($this->changeDate);
    }

    public function getHash(): string
    {
        return $this->id->value;
    }
}
