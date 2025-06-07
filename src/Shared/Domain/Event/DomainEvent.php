<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

use App\Shared\Domain\ValueObject\DateTime;

/**
 * Class DomainEvent
 *
 * @package App\Shared\Domain\Event
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
abstract class DomainEvent implements DomainEventInterface
{
    private readonly string $occurredOn;

    public function __construct(
        private readonly string $aggregateId,
        private readonly string $performerId,
        string $occurredOn = null
    ) {
        $this->occurredOn = $occurredOn ?: (new DateTime())->getValue();
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getOccurredOn(): string
    {
        return $this->occurredOn;
    }

    public function getPerformerId(): string
    {
        return $this->performerId;
    }
}
