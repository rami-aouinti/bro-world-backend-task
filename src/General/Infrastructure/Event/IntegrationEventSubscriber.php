<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Event;

use App\General\Application\Bus\Event\DomainEventBusInterface;
use App\General\Application\Bus\Event\IntegrationEventSubscriberInterface;
use App\General\Domain\Service\DomainEventFactoryInterface;

/**
 * Class IntegrationEventSubscriber
 *
 * @package App\General\Infrastructure\Event
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class IntegrationEventSubscriber implements IntegrationEventSubscriberInterface
{
    public function __construct(
        private DomainEventFactoryInterface $factory,
        private DomainEventBusInterface $eventBus
    ) {
    }

    public function __invoke(IntegrationEvent $event): void
    {
        $this->eventBus->dispatch(...$this->factory->create(
            $event->getDomainEventName(),
            $event->getAggregateId(),
            $event->getBody(),
            $event->getPerformerId(),
            $event->getOccurredOn()
        ));
    }
}
