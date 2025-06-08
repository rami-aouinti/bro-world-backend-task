<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Bus;

use App\General\Application\Bus\Event\DomainEventBusInterface;
use App\General\Domain\Event\DomainEventInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SymfonyDomainEventBus
 *
 * @package App\General\Infrastructure\Bus
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
