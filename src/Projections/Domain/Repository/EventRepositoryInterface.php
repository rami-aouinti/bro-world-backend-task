<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\General\Domain\ValueObject\DateTime;
use App\Projections\Domain\Entity\Event;

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
