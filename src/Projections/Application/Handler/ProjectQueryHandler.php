<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\General\Application\Service\CurrentUserExtractorInterface;
use App\General\Infrastructure\ValueObject\SymfonyUser;
use App\Projections\Application\Query\ProjectQuery;
use App\Projections\Domain\Entity\ProjectProjection;
use App\Projections\Domain\Exception\InsufficientPermissionsException;
use App\Projections\Domain\Exception\ObjectDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\General\Application\Bus\Query\QueryHandlerInterface;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function sprintf;

/**
 * Class ProjectQueryHandler
 *
 * @package App\Projections\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsMessageHandler]
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
