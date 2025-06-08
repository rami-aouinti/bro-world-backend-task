<?php

declare(strict_types=1);

namespace App\Projects\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

/**
 * Class ProjectOwnerWasChangedEvent
 *
 * @package App\Projects\Domain\Event
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectOwnerWasChangedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $ownerId,
        string $performerId,
        string $occurredOn = null
    ) {
        parent::__construct($id, $performerId, $occurredOn);
    }

    public static function getEventName(): string
    {
        return 'project.ownerChanged';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $performerId,
        string $occurredOn
    ): static {
        return new self($aggregateId, $body['ownerId'], $performerId, $occurredOn);
    }

    public function toPrimitives(): array
    {
        return [
            'ownerId' => $this->ownerId,
        ];
    }
}
