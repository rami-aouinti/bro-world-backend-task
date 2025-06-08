<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\EventStore;

use App\Projections\Domain\DTO\DomainEventEnvelope;

/**
 * Class EventStreamFactory
 *
 * @package App\Projections\Domain\Service\EventStore
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class EventStreamFactory implements EventStreamFactoryInterface
{
    public function __construct(private ?EventStreamFilterInterface $streamFilter = null)
    {
    }

    /**
     * @param DomainEventEnvelope[] $envelopes
     */
    public function createStream(array $envelopes): EventStreamInterface
    {
        return new EventStream($this->filterEnvelopes($envelopes));
    }

    /**
     * @param DomainEventEnvelope[] $envelopes
     *
     * @return DomainEventEnvelope[]
     */
    private function filterEnvelopes(array $envelopes): array
    {
        if ($this->streamFilter === null) {
            return $envelopes;
        }

        $result = [];
        foreach ($envelopes as $key => $envelope) {
            if ($this->streamFilter->isSuitable($envelope->event)) {
                $result[$key] = $envelope;
            }
        }

        return $result;
    }
}
