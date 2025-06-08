<?php

declare(strict_types=1);

namespace App\General\Application\OptimisticLock;

use App\General\Domain\Aggregate\AggregateRoot;

interface OptimisticLockManagerInterface
{
    public function lock(AggregateRoot $aggregateRoot, int $expectedVersion): int;
}
