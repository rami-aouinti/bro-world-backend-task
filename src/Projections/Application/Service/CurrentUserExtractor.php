<?php

declare(strict_types=1);

namespace App\Projections\Application\Service;

use App\Projections\Domain\Entity\UserProjection;
use App\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use App\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Shared\Domain\Exception\UserDoesNotExistException;

final readonly class CurrentUserExtractor implements CurrentUserExtractorInterface
{
    public function __construct(
        private UserProjectionRepositoryInterface $repository,
        private AuthenticatorServiceInterface $authenticator,
    ) {
    }

    public function extract(): UserProjection
    {
        $userId = $this->authenticator->getUserId();
        $user = $this->repository->findById($userId->value);

        if (null === $user) {
            throw new UserDoesNotExistException($userId->value);
        }

        return $user;
    }
}
