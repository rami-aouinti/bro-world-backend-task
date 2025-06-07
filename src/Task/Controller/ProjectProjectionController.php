<?php

declare(strict_types=1);

namespace App\Task\Controller;

use App\General\Infrastructure\ValueObject\SymfonyUser;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
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
use App\Shared\Application\Bus\Query\QueryBusInterface;
use App\Shared\Application\Paginator\Pagination;
use App\Shared\Infrastructure\Criteria\QueryCriteriaFromRequestConverterInterface;
use App\Shared\Infrastructure\Criteria\RequestCriteriaDTO;
use App\Shared\Infrastructure\Paginator\PaginationResponseDTO;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class ProjectProjectionController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
#[Route('/api/projects', name: 'project.')]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
final readonly class ProjectProjectionController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private QueryCriteriaFromRequestConverterInterface $converter
    ) {
    }

    #[Route('/', name: 'getAll', methods: ['GET'])]
    public function __invoke(SymfonyUser $user, RequestCriteriaDTO $criteria): JsonResponse
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

    #[Route('/{id}/', name: 'get', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $project = $this->queryBus->dispatch(new ProjectQuery($id));

        return new JsonResponse(ProjectResponseDTO::create($project));
    }

    #[Route('/{id}/requests/', name: 'getAllRequests', methods: ['GET'])]
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

    #[Route('/{id}/tasks/', name: 'getAllTasks', methods: ['GET'])]
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

    #[Route('/{id}/participants/', name: 'getAllParticipants', methods: ['GET'])]
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
