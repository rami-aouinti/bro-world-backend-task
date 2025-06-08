<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\EventStore;

use App\Shared\Domain\Event\DomainEventInterface;

/**
 *
 */
interface EventStreamFilterInterface
{
    public function isSuitable(DomainEventInterface $domainEvent): bool;
}
