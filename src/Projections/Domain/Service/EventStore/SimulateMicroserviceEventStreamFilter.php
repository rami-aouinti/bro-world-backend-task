<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\EventStore;

use App\General\Domain\Event\DomainEventInterface;

// For development purposes only: app domains pretend to be microservices

/**
 * Class SimulateMicroserviceEventStreamFilter
 *
 * @package App\Projections\Domain\Service\EventStore
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class SimulateMicroserviceEventStreamFilter implements EventStreamFilterInterface
{
    public function isSuitable(DomainEventInterface $domainEvent): bool
    {
        $eventDomain = explode('\\', $domainEvent::class)[1] ?? null;
        $projectionsDomain = explode('\\', self::class)[1] ?? null;

        if ($eventDomain === null || $projectionsDomain === null) {
            return false;
        }

        return $eventDomain === $projectionsDomain;
    }
}
