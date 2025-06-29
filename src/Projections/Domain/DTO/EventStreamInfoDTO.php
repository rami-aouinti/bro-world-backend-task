<?php

declare(strict_types=1);

namespace App\Projections\Domain\DTO;

use App\General\Domain\ValueObject\DateTime;
use App\Projections\Domain\Service\EventStore\EventStreamInterface;

/**
 * Class EventStreamInfoDTO
 *
 * @package App\Projections\Domain\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class EventStreamInfoDTO
{
    public function __construct(
        public EventStreamInterface $stream,
        public ?DateTime $lastPosition
    ) {
    }
}
