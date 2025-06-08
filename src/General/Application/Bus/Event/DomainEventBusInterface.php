<?php

declare(strict_types=1);

namespace App\General\Application\Bus\Event;

use App\General\Domain\Event\DomainEventInterface;

/**
 *
 */
interface DomainEventBusInterface
{
    public function dispatch(DomainEventInterface ...$events): void;
}
