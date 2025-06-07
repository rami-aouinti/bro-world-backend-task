<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use App\Shared\Application\Bus\Event\DomainEventBusInterface;
use App\Shared\Domain\Event\DomainEventInterface;

final readonly class SymfonyDomainEventBus implements DomainEventBusInterface
{
    public function __construct(private MessageBusInterface $domainEventBus)
    {
    }

    public function dispatch(DomainEventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->domainEventBus->dispatch($event);
        }
    }
}
