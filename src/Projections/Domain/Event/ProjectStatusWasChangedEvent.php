<?php

declare(strict_types=1);

namespace App\Projections\Domain\Event;

use App\General\Domain\Event\DomainEvent;

final class ProjectStatusWasChangedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $status,
        string $performerId,
        string $occurredOn = null
    ) {
        parent::__construct($id, $performerId, $occurredOn);
    }

    public static function getEventName(): string
    {
        return 'project.statusChanged';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $performerId,
        string $occurredOn
    ): static {
        return new self($aggregateId, $body['status'], $performerId, $occurredOn);
    }

    public function toPrimitives(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}
