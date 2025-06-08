<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\General\Infrastructure\ValueObject\SymfonyUser;
use App\Projections\Application\Query\ProjectQuery;
use App\Projections\Domain\Entity\ProjectProjection;
use App\Projections\Domain\Exception\InsufficientPermissionsException;
use App\Projections\Domain\Exception\ObjectDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\General\Application\Bus\Query\QueryHandlerInterface;

use function sprintf;

/**
 * Class ProjectQueryHandler
 *
 * @package App\Projections\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectProjectionRepositoryInterface $repository,
    ) {
    }

    public function __invoke(ProjectQuery $query, SymfonyUser $user): ProjectProjection
    {
        $projectBydId = $this->repository->findById($query->id);
        if ($projectBydId === null) {
            throw new ObjectDoesNotExistException(sprintf('Project "%s" does not exist.', $query->id));
        }

        $project = $this->repository->findByIdAndUserId($query->id, $user->getUserIdentifier());
        if ($project === null) {
            throw new InsufficientPermissionsException(
                sprintf('Insufficient permissions to view the project "%s".', $query->id));
        }

        return $project;
    }
}
