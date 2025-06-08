<?php

declare(strict_types=1);

namespace App\General\Domain\Aggregate;

use App\General\Domain\Equatable;
use App\General\Domain\Event\DomainEventInterface;
use App\General\Domain\ValueObject\Uuid;

/**
 * Class AggregateRoot
 *
 * @package App\General\Domain\Aggregate
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
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
