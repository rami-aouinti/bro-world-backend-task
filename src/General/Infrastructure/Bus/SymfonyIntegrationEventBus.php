<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Bus;

use App\General\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Domain\Event\DomainEventInterface;
use App\General\Infrastructure\Event\IntegrationEvent;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SymfonyIntegrationEventBus
 *
 * @package App\General\Infrastructure\Bus
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class SymfonyIntegrationEventBus implements IntegrationEventBusInterface
{
    public function __construct(private MessageBusInterface $integrationEventBus)
    {
    }

    /**
     * @param DomainEventInterface[] $events
     * @param int|null               $version
     *
     * @throws ExceptionInterface
     */
    public function dispatch(array $events, int $version = null): void
    {
        foreach ($events as $event) {
            $integrationEvent = new IntegrationEvent($event, $version);

            $this->integrationEventBus->dispatch($integrationEvent);
        }
    }
}
