<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\EventStore;

use App\Projections\Domain\DTO\DomainEventEnvelope;

interface EventStreamFactoryInterface
{
    /**
     * @param DomainEventEnvelope[] $envelopes
     */
    public function createStream(array $envelopes): EventStreamInterface;
}
