<?php

declare(strict_types=1);

namespace App\Projects\Domain\Event;

use App\General\Domain\Event\DomainEvent;

/**
 * Class TaskWasCreatedEvent
 *
 * @package App\Projects\Domain\Event
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskWasCreatedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $projectId,
        public readonly string $name,
        public readonly string $brief,
        public readonly string $description,
        public readonly string $startDate,
        public readonly string $finishDate,
        public readonly string $status,
        public readonly string $ownerId,
        string $performerId,
        string $occurredOn = null
    ) {
        parent::__construct($id, $performerId, $occurredOn);
    }

    public static function getEventName(): string
    {
        return 'task.created';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $performerId,
        string $occurredOn
    ): static {
        return new self(
            $aggregateId,
            $body['projectId'],
            $body['name'],
            $body['brief'],
            $body['description'],
            $body['startDate'],
            $body['finishDate'],
            $body['status'],
            $body['ownerId'],
            $performerId,
            $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return [
            'projectId' => $this->projectId,
            'name' => $this->name,
            'brief' => $this->brief,
            'description' => $this->description,
            'startDate' => $this->startDate,
            'finishDate' => $this->finishDate,
            'status' => $this->status,
            'ownerId' => $this->ownerId,
        ];
    }
}
