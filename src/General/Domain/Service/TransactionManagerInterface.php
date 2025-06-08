<?php

declare(strict_types=1);

namespace App\General\Domain\Service;

/**
 *
 */
interface TransactionManagerInterface
{
    public function withTransaction(callable $callback): void;
}
