<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\Projections\Domain\Entity\Event;
use App\Shared\Domain\ValueObject\DateTime;

/**
 *
 */
interface EventRepositoryInterface
{
    /**
     * @return Event[]
     */
    public function findOrderedFromLastTime(?DateTime $lastDatetime): array;

    public function save(Event $event): void;
}
