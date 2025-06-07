<?php

declare(strict_types=1);

namespace App\Task\Controller\Api;

use App\General\Infrastructure\ValueObject\SymfonyUser;
use App\Projections\Application\Query\ProjectListQuery;
use App\Projections\Infrastructure\DTO\ProjectListResponseDTO;
use App\Shared\Application\Bus\Query\QueryBusInterface;
use App\Shared\Application\Paginator\Pagination;
use App\Shared\Infrastructure\Criteria\QueryCriteriaFromRequestConverterInterface;
use App\Shared\Infrastructure\Criteria\RequestCriteriaDTO;
use App\Shared\Infrastructure\Paginator\PaginationResponseDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class ProjectController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
#[Route('/projects', name: 'platform_project.')]
final readonly class GetProjectsController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private QueryCriteriaFromRequestConverterInterface $converter
    ) {
    }

    #[Route('/', name: 'getAll', methods: ['GET'])]
    public function __invoke(
        #[MapRequestPayload] RequestCriteriaDTO $criteria,
        SymfonyUser $user): JsonResponse
    {
        /** @var Pagination $pagination */
        $pagination = $this->queryBus->dispatch(
            new ProjectListQuery($this->converter->convert($criteria))
        );

        return new JsonResponse(PaginationResponseDTO::createFromPagination(
            $pagination,
            static fn (array $items) => ProjectListResponseDTO::createList($items)
        ));
    }
}
