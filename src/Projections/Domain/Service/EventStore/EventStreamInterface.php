<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\EventStore;

use App\Projections\Domain\DTO\DomainEventEnvelope;

interface EventStreamInterface
{
    public function next(): ?DomainEventEnvelope;

    public function eventCount(): int;
}
