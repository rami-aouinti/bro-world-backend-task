<?php

declare(strict_types=1);

namespace App\Projects\Domain\Collection;

use App\Projects\Domain\Exception\ProjectParticipantDoesNotExistException;
use App\Projects\Domain\Exception\UserIsAlreadyProjectParticipantException;
use App\Projects\Domain\ValueObject\Participant;
use App\Shared\Domain\Collection\ManagedCollection;
use App\General\Domain\ValueObject\UserId;

/**
 * Class ParticipantCollection
 *
 * @package App\Projects\Domain\Collection
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ParticipantCollection extends ManagedCollection
{
    public function ensureUserIsParticipant(UserId $userId): void
    {
        if (!$this->exists($userId->value)) {
            throw new ProjectParticipantDoesNotExistException($userId->value);
        }
    }

    public function ensureUserIsNotParticipant(UserId $userId): void
    {
        if ($this->exists($userId->value)) {
            throw new UserIsAlreadyProjectParticipantException($userId->value);
        }
    }

    /**
     * @return class-string
     */
    protected function supportClass(): string
    {
        return Participant::class;
    }
}
