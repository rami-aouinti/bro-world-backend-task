<?php

declare(strict_types=1);

namespace App\Task\Controller\Api;

use App\General\Application\Bus\Query\QueryBusInterface;
use App\General\Application\Paginator\Pagination;
use App\General\Infrastructure\Criteria\QueryCriteriaFromRequestConverterInterface;
use App\General\Infrastructure\Criteria\RequestCriteriaDTO;
use App\General\Infrastructure\Paginator\PaginationResponseDTO;
use App\Projections\Application\Query\ProjectListQuery;
use App\Projections\Infrastructure\DTO\ProjectListResponseDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

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
    public function get(RequestCriteriaDTO $criteria): JsonResponse
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
