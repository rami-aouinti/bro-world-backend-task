<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Application\Bus\Event\DomainEventBusInterface;
use App\Shared\Application\Bus\Event\IntegrationEventSubscriberInterface;
use App\Shared\Domain\Service\DomainEventFactoryInterface;

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
