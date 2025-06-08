<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\UserIsNotTaskOwnerException;
use App\Shared\Domain\Equatable;
use App\General\Domain\ValueObject\UserId;

/**
 * Class TaskOwner
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskOwner implements Equatable
{
    public function __construct(
        public UserId $id
    ) {
    }

    public function ensureUserIsOwner(UserId $userId): void
    {
        if (!$this->userIsOwner($userId)) {
            throw new UserIsNotTaskOwnerException($userId->value);
        }
    }

    public function userIsOwner(UserId $userId): bool
    {
        return $this->id->equals($userId);
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->id->equals($this->id);
    }
}
