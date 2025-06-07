<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus\Event;

use App\Shared\Domain\Event\DomainEventInterface;

/**
 *
 */
interface IntegrationEventBusInterface
{
    /**
     * @param DomainEventInterface[] $events
     */
    public function dispatch(array $events, int $version = null): void;
}
