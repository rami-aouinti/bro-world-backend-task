<?php

declare(strict_types=1);

namespace App\Projects\Domain\Event;

use App\General\Domain\Event\DomainEvent;

/**
 * Class ProjectTaskFinishDateWasChangedEvent
 *
 * @package App\Projects\Domain\Event
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectTaskFinishDateWasChangedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $taskId,
        public readonly string $finishDate,
        string $performerId,
        string $occurredOn = null
    ) {
        parent::__construct($id, $performerId, $occurredOn);
    }

    public static function getEventName(): string
    {
        return 'projectTask.finishDateChanged';
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
            $body['finishDate'],
            $performerId,
            $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return [
            'taskId' => $this->taskId,
            'finishDate' => $this->finishDate,
        ];
    }
}
