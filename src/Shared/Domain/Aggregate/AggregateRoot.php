<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Equatable;
use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\ValueObject\Uuid;

abstract class AggregateRoot implements Equatable
{
    /**
     * @var DomainEventInterface[]
     */
    private array $events = [];

    public function registerEvent(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return DomainEventInterface[]
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    abstract public function getId(): Uuid;
}
