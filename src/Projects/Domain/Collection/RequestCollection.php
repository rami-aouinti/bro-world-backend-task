<?php

declare(strict_types=1);

namespace App\Projects\Domain\Collection;

use App\Projects\Domain\Entity\Request;
use App\Projects\Domain\Exception\UserAlreadyHasPendingRequestException;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Shared\Domain\Collection\ManagedCollection;
use App\General\Domain\ValueObject\UserId;

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

        if (null !== $request) {
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
