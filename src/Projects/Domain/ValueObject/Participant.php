<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\General\Domain\Equatable;
use App\General\Domain\Hashable;
use App\General\Domain\ValueObject\UserId;

/**
 * Class Participant
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class Participant implements Equatable, Hashable
{
    public function __construct(
        public ProjectId $projectId,
        public UserId $userId
    ) {
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->projectId->equals($this->projectId)
            && $other->userId->equals($this->userId);
    }

    public function getHash(): string
    {
        return $this->userId->value;
    }
}
