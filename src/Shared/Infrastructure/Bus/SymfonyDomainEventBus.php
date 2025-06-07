<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Shared\Application\Bus\Event\DomainEventBusInterface;
use App\Shared\Domain\Event\DomainEventInterface;

/**
 * Class SymfonyDomainEventBus
 *
 * @package App\Shared\Infrastructure\Bus
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class SymfonyDomainEventBus implements DomainEventBusInterface
{
    public function __construct(private MessageBusInterface $domainEventBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(DomainEventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->domainEventBus->dispatch($event);
        }
    }
}
