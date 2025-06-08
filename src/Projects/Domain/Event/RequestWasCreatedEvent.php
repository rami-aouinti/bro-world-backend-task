<?php

declare(strict_types=1);

namespace App\Projects\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

/**
 * Class RequestWasCreatedEvent
 *
 * @package App\Projects\Domain\Event
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class RequestWasCreatedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $requestId,
        public readonly string $userId,
        public readonly string $status,
        public readonly string $changeDate,
        string $performerId,
        string $occurredOn = null
    ) {
        parent::__construct($id, $performerId, $occurredOn);
    }

    public static function getEventName(): string
    {
        return 'project.requestCreated';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $performerId,
        string $occurredOn
    ): static {
        return new self(
            $aggregateId,
            $body['requestId'],
            $body['userId'],
            $body['status'],
            $body['changeDate'],
            $performerId,
            $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return [
            'requestId' => $this->requestId,
            'userId' => $this->userId,
            'status' => $this->status,
            'changeDate' => $this->changeDate,
        ];
    }
}
