<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus\Event;

use App\Shared\Domain\Event\DomainEventInterface;

interface DomainEventBusInterface
{
    public function dispatch(DomainEventInterface ...$events): void;
}
