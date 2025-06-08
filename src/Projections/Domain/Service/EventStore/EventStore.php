<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\EventStore;

use App\General\Domain\Service\DomainEventFactoryInterface;
use App\General\Domain\ValueObject\DateTime;
use App\Projections\Domain\DTO\DomainEventEnvelope;
use App\Projections\Domain\DTO\EventStreamInfoDTO;
use App\Projections\Domain\Repository\EventRepositoryInterface;
use Exception;

use function count;

/**
 * Class EventStore
 *
 * @package App\Projections\Domain\Service\EventStore
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class EventStore implements EventStoreInterface
{
    public function __construct(
        private DomainEventFactoryInterface $eventFactory,
        private EventRepositoryInterface $repository,
        private EventStreamFactoryInterface $streamFactory
    ) {
    }

    /**
     * @throws Exception
     */
    public function getStreamInfo(?DateTime $lastDatetime): EventStreamInfoDTO
    {
        $events = $this->repository->findOrderedFromLastTime($lastDatetime);

        /** @var DomainEventEnvelope[] $envelopes */
        $envelopes = [];
        foreach ($events as $event) {
            $envelopes = array_merge($envelopes, $event->createEventEnvelope($this->eventFactory));
        }

        $position = $lastDatetime;
        if (count($envelopes) > 0) {
            $position = new DateTime(array_reverse($envelopes)[0]->event->getOccurredOn());
        }

        return new EventStreamInfoDTO(
            $this->streamFactory->createStream($envelopes),
            $position
        );
    }
}
