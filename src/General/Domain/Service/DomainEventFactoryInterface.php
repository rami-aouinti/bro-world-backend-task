<?php

declare(strict_types=1);

namespace App\General\Domain\Service;

use App\General\Domain\Event\DomainEventInterface;

/**
 *
 */
interface DomainEventFactoryInterface
{
    /**
     * @return DomainEventInterface[]
     */
    public function create(
        string $eventName,
        string $aggregateId,
        array $body,
        string $performerId,
        string $occurredOn
    ): array;
}
