<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\EventStore;

use App\General\Domain\ValueObject\DateTime;
use App\Projections\Domain\DTO\EventStreamInfoDTO;

interface EventStoreInterface
{
    public function getStreamInfo(?DateTime $lastDatetime): EventStreamInfoDTO;
}
