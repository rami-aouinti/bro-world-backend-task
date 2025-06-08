<?php

declare(strict_types=1);

namespace App\General\Application\Bus\Command;

/**
 *
 */
interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): mixed;
}
