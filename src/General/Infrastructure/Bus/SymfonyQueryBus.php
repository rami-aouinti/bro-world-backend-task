<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Bus;

use App\General\Application\Bus\Query\QueryBusInterface;
use App\General\Application\Bus\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SymfonyQueryBus
 *
 * @package App\General\Infrastructure\Bus
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class SymfonyQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function dispatch(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
