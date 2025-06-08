<?php

declare(strict_types=1);

namespace App\General\Application\Bus\Event;

use App\General\Domain\Event\DomainEventInterface;

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
