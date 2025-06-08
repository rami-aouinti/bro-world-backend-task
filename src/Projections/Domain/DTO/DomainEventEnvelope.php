<?php

declare(strict_types=1);

namespace App\Projections\Domain\DTO;

use App\General\Domain\Event\DomainEventInterface;

final readonly class DomainEventEnvelope
{
    public function __construct(
        public DomainEventInterface $event,
        public ?int $version
    ) {
    }
}
