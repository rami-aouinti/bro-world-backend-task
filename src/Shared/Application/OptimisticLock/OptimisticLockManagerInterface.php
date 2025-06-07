<?php

declare(strict_types=1);

namespace App\Shared\Application\OptimisticLock;

use App\Shared\Domain\Aggregate\AggregateRoot;

interface OptimisticLockManagerInterface
{
    public function lock(AggregateRoot $aggregateRoot, int $expectedVersion): int;
}
