<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Shared\Application\Bus\Event\IntegrationEventBusInterface;
use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Infrastructure\Event\IntegrationEvent;

/**
 * Class SymfonyIntegrationEventBus
 *
 * @package App\Shared\Infrastructure\Bus
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
