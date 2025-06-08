<?php

declare(strict_types=1);

namespace App\Projects\Domain\Event;

use App\General\Domain\Event\DomainEvent;

/**
 * Class ProjectTaskWasCreatedEvent
 *
 * @package App\Projects\Domain\Event
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectTaskWasCreatedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $taskId,
        public readonly string $ownerId,
        string $performerId,
        string $occurredOn = null
    ) {
        parent::__construct($id, $performerId, $occurredOn);
    }

    public static function getEventName(): string
    {
        return 'projectTask.created';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $performerId,
        string $occurredOn
    ): static {
        return new self(
            $aggregateId,
            $body['taskId'],
            $body['ownerId'],
            $performerId,
            $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return [
            'taskId' => $this->taskId,
            'ownerId' => $this->ownerId,
        ];
    }
}
