<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Shared\Domain\Equatable;
use App\Shared\Domain\Hashable;
use App\General\Domain\ValueObject\UserId;

/**
 * Class ProjectTask
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectTask implements Equatable, Hashable
{
    public function __construct(
        public ProjectId $projectId,
        public TaskId $taskId,
        public UserId $userId
    ) {
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->projectId->equals($this->projectId)
            && $other->taskId->equals($this->taskId)
            && $other->userId->equals($this->userId);
    }

    public function getHash(): string
    {
        return $this->taskId->value;
    }
}
