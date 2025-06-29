<?php

declare(strict_types=1);

namespace App\Task\Controller\Api;

use App\General\Application\Bus\Query\QueryBusInterface;
use App\General\Application\Paginator\Pagination;
use App\General\Infrastructure\Criteria\QueryCriteriaFromRequestConverterInterface;
use App\General\Infrastructure\Criteria\RequestCriteriaDTO;
use App\General\Infrastructure\Paginator\PaginationResponseDTO;
use App\Projections\Application\Query\TaskLinkQuery;
use App\Projections\Application\Query\TaskQuery;
use App\Projections\Infrastructure\DTO\TaskLinkResponseDTO;
use App\Projections\Infrastructure\DTO\TaskResponseDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class TaskProjectionController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
#[Route('/api/tasks', name: 'task.')]
final readonly class TaskProjectionController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private QueryCriteriaFromRequestConverterInterface $converter
    ) {
    }

    #[Route('/{id}/', name: 'get', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $task = $this->queryBus->dispatch(new TaskQuery($id));

        return new JsonResponse(TaskResponseDTO::create($task));
    }

    #[Route('/{id}/links/', name: 'getAllLinks', methods: ['GET'])]
    public function getAllLinks(string $id, RequestCriteriaDTO $criteria): JsonResponse
    {
        /** @var Pagination $pagination */
        $pagination = $this->queryBus->dispatch(
            new TaskLinkQuery($id, $this->converter->convert($criteria))
        );

        return new JsonResponse(PaginationResponseDTO::createFromPagination(
            $pagination,
            static fn (array $items) => TaskLinkResponseDTO::createList($items)
        ));
    }
}
