<?php

declare(strict_types=1);

namespace App\Projections\Application\Subscriber;

use App\General\Application\Bus\Event\IntegrationEventInterface;
use App\General\Application\Bus\Event\IntegrationEventSubscriberInterface;
use App\General\Application\Service\UuidGeneratorInterface;
use App\General\Domain\Service\DomainEventFactoryInterface;
use App\Projections\Domain\Entity\Event;
use App\Projections\Domain\Repository\EventRepositoryInterface;
use App\Projections\Domain\Service\EventStore\EventStreamFilterInterface;
use Exception;

/**
 * Class StoreEventOnIntegrationEventTriggered
 *
 * @package App\Projections\Application\Subscriber
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class StoreEventOnIntegrationEventTriggered implements IntegrationEventSubscriberInterface
{
    public function __construct(
        private EventRepositoryInterface $repository,
        private UuidGeneratorInterface $uuidGenerator,
        private DomainEventFactoryInterface $eventFactory,
        // For development purposes only: app domains pretend to be microservices
        private EventStreamFilterInterface $streamFilter
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(IntegrationEventInterface $integrationEvent): void
    {
        $domainEvents = $this->eventFactory->create(
            $integrationEvent->getDomainEventName(),
            $integrationEvent->getAggregateId(),
            $integrationEvent->getBody(),
            $integrationEvent->getPerformerId(),
            $integrationEvent->getOccurredOn()
        );

        foreach ($domainEvents as $domainEvent) {
            // For development purposes only: app domains pretend to be microservices
            if (!$this->streamFilter->isSuitable($domainEvent)) {
                continue;
            }

            $event = Event::fromDomainEvent(
                $this->uuidGenerator->generate(),
                $domainEvent,
                $integrationEvent->getVersion()
            );

            $this->repository->save($event);
        }
    }
}
