<?php

declare(strict_types=1);

namespace App\Projections\Domain\DTO;

use App\Projections\Domain\Service\EventStore\EventStreamInterface;
use App\Shared\Domain\ValueObject\DateTime;

final readonly class EventStreamInfoDTO
{
    public function __construct(
        public EventStreamInterface $stream,
        public ?DateTime $lastPosition
    ) {
    }
}
