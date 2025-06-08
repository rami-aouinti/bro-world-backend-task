<?php

declare(strict_types=1);

namespace App\General\Application\Bus\Query;

/**
 *
 */
interface QueryBusInterface
{
    public function dispatch(QueryInterface $query): mixed;
}
