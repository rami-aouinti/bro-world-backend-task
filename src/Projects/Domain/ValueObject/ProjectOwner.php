<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\UserIsAlreadyProjectOwnerException;
use App\Projects\Domain\Exception\UserIsNotProjectOwnerException;
use App\Shared\Domain\Equatable;
use App\General\Domain\ValueObject\UserId;

/**
 * Class ProjectOwner
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectOwner implements Equatable
{
    public function __construct(
        public UserId $id
    ) {
    }

    public function ensureUserIsOwner(UserId $userId): void
    {
        if (!$this->userIsOwner($userId)) {
            throw new UserIsNotProjectOwnerException($userId->value);
        }
    }

    public function ensureUserIsNotOwner(UserId $userId): void
    {
        if ($this->userIsOwner($userId)) {
            throw new UserIsAlreadyProjectOwnerException($userId->value);
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
