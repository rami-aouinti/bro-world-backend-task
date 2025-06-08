<?php

declare(strict_types=1);

namespace App\Projects\Domain\Collection;

use App\General\Domain\Collection\ManagedCollection;
use App\General\Domain\ValueObject\UserId;
use App\Projects\Domain\Entity\Request;
use App\Projects\Domain\Exception\UserAlreadyHasPendingRequestException;
use App\Projects\Domain\ValueObject\ProjectId;

/**
 * Class RequestCollection
 *
 * @package App\Projects\Domain\Collection
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class RequestCollection extends ManagedCollection
{
    public function ensureUserDoesNotHavePendingRequest(UserId $userId, ProjectId $projectId): void
    {
        $request = null;

        /** @var Request $item */
        foreach ($this->getItems() as $item) {
            if ($item->isPendingForUser($userId)) {
                $request = $item;
                break;
            }
        }

        if ($request !== null) {
            throw new UserAlreadyHasPendingRequestException($userId->value, $projectId->value);
        }
    }

    /**
     * @return class-string
     */
    protected function supportClass(): string
    {
        return Request::class;
    }
}
