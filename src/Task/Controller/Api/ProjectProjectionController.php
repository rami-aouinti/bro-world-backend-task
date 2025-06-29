<?php

declare(strict_types=1);

namespace App\Task\Controller\Api;

use App\General\Application\Bus\Query\QueryBusInterface;
use App\General\Application\Paginator\Pagination;
use App\General\Infrastructure\Criteria\QueryCriteriaFromRequestConverterInterface;
use App\General\Infrastructure\Criteria\RequestCriteriaDTO;
use App\General\Infrastructure\Paginator\PaginationResponseDTO;
use App\Projections\Application\Query\ProjectListQuery;
use App\Projections\Application\Query\ProjectParticipantQuery;
use App\Projections\Application\Query\ProjectQuery;
use App\Projections\Application\Query\ProjectRequestQuery;
use App\Projections\Application\Query\TaskListQuery;
use App\Projections\Infrastructure\DTO\ProjectListResponseDTO;
use App\Projections\Infrastructure\DTO\ProjectParticipantResponseDTO;
use App\Projections\Infrastructure\DTO\ProjectRequestResponseDTO;
use App\Projections\Infrastructure\DTO\ProjectResponseDTO;
use App\Projections\Infrastructure\DTO\TaskListResponseDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class ProjectProjectionController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
final readonly class ProjectProjectionController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private QueryCriteriaFromRequestConverterInterface $converter
    ) {
    }

    #[Route('/api/projects', name: 'projects.getAll', methods: ['GET'])]
    public function __invoke(RequestCriteriaDTO $criteria): JsonResponse
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

    #[Route('/api/projects/{id}', name: 'projects.get', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $project = $this->queryBus->dispatch(new ProjectQuery($id));

        return new JsonResponse(ProjectResponseDTO::create($project));
    }

    #[Route('/api/projects/{id}/requests', name: 'getAllRequests', methods: ['GET'])]
    public function getAllRequests(string $id, RequestCriteriaDTO $criteria): JsonResponse
    {
        /** @var Pagination $pagination */
        $pagination = $this->queryBus->dispatch(
            new ProjectRequestQuery($id, $this->converter->convert($criteria))
        );

        return new JsonResponse(PaginationResponseDTO::createFromPagination(
            $pagination,
            static fn (array $items) => ProjectRequestResponseDTO::createList($items)
        ));
    }

    #[Route('/api/projects/{id}/tasks', name: 'projects.getAllTasks', methods: ['GET'])]
    public function getAllTasks(string $id, RequestCriteriaDTO $criteria): JsonResponse
    {
        /** @var Pagination $pagination */
        $pagination = $this->queryBus->dispatch(
            new TaskListQuery($id, $this->converter->convert($criteria))
        );

        return new JsonResponse(PaginationResponseDTO::createFromPagination(
            $pagination,
            static fn (array $items) => TaskListResponseDTO::createList($items)
        ));
    }

    #[Route('/api/projects/{id}/participants', name: 'projects.getAllParticipants', methods: ['GET'])]
    public function getAllParticipants(string $id, RequestCriteriaDTO $criteria): JsonResponse
    {
        /** @var Pagination $pagination */
        $pagination = $this->queryBus->dispatch(
            new ProjectParticipantQuery($id, $this->converter->convert($criteria))
        );

        return new JsonResponse(PaginationResponseDTO::createFromPagination(
            $pagination,
            static fn (array $items) => ProjectParticipantResponseDTO::createList($items)
        ));
    }
}
