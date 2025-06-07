<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\Projections\Application\Query\UserProfileQuery;
use App\Projections\Application\Service\CurrentUserExtractorInterface;
use App\Projections\Domain\Entity\UserProjection;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;

final readonly class UserProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct(private CurrentUserExtractorInterface $userExtractor)
    {
    }

    public function __invoke(UserProfileQuery $query): UserProjection
    {
        return $this->userExtractor->extract();
    }
}
