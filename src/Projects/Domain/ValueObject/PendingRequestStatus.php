<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

/**
 * Class PendingRequestStatus
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class PendingRequestStatus extends RequestStatus
{
    protected function getNextStatuses(): array
    {
        return [
            RejectedRequestStatus::class,
            ConfirmedRequestStatus::class,
        ];
    }
}
