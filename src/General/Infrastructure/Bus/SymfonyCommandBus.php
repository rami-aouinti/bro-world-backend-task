<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Bus;

use App\General\Application\Bus\Command\CommandBusInterface;
use App\General\Application\Bus\Command\CommandInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SymfonyCommandBus
 *
 * @package App\General\Infrastructure\Bus
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class SymfonyCommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function dispatch(CommandInterface $command): mixed
    {
        return $this->handle($command);
    }
}
