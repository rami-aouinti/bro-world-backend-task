<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\Projections\Application\Query\ProjectQuery;
use App\Projections\Application\Service\CurrentUserExtractorInterface;
use App\Projections\Domain\Entity\ProjectProjection;
use App\Projections\Domain\Exception\InsufficientPermissionsException;
use App\Projections\Domain\Exception\ObjectDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;

final readonly class ProjectQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectProjectionRepositoryInterface $repository,
        private CurrentUserExtractorInterface $userExtractor
    ) {
    }

    public function __invoke(ProjectQuery $query): ProjectProjection
    {
        $user = $this->userExtractor->extract();

        $projectBydId = $this->repository->findById($query->id);
        if (null === $projectBydId) {
            throw new ObjectDoesNotExistException(sprintf('Project "%s" does not exist.', $query->id));
        }

        $project = $this->repository->findByIdAndUserId($query->id, $user->getId());
        if (null === $project) {
            throw new InsufficientPermissionsException(sprintf('Insufficient permissions to view the project "%s".', $query->id));
        }

        return $project;
    }
}
