<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\EventStore;

use App\Projections\Domain\DTO\EventStreamInfoDTO;
use App\Shared\Domain\ValueObject\DateTime;

interface EventStoreInterface
{
    public function getStreamInfo(?DateTime $lastDatetime): EventStreamInfoDTO;
}
