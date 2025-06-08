<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\Projections\Domain\DTO\DomainEventEnvelope;

/**
 *
 */
interface ProjectorInterface
{
    public function projectWhen(DomainEventEnvelope $envelope): void;

    public function priority(): int;

    public function flush(): void;
}
