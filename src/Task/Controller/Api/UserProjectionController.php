<?php

declare(strict_types=1);

namespace App\Task\Controller\Api;

use App\General\Application\Bus\Query\QueryBusInterface;
use App\General\Application\Paginator\Pagination;
use App\General\Infrastructure\Criteria\QueryCriteriaFromRequestConverterInterface;
use App\General\Infrastructure\Criteria\RequestCriteriaDTO;
use App\General\Infrastructure\Paginator\PaginationResponseDTO;
use App\Projections\Application\Query\UserProfileQuery;
use App\Projections\Application\Query\UserProjectQuery;
use App\Projections\Application\Query\UserRequestQuery;
use App\Projections\Infrastructure\DTO\ProjectListResponseDTO;
use App\Projections\Infrastructure\DTO\UserRequestResponseDTO;
use App\Projections\Infrastructure\DTO\UserResponseDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class UserProjectionController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
final readonly class UserProjectionController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private QueryCriteriaFromRequestConverterInterface $converter
    ) {
    }

    #[Route('/api/users', name: 'user.getInfo', methods: ['GET'])]
    public function getInfo(): JsonResponse
    {
        $user = $this->queryBus->dispatch(new UserProfileQuery());

        return new JsonResponse(UserResponseDTO::create($user));
    }

    #[Route('/api/users/requests', name: 'user.getAllRequests', methods: ['GET'])]
    public function getAllRequests(RequestCriteriaDTO $criteria): JsonResponse
    {
        /** @var Pagination $pagination */
        $pagination = $this->queryBus->dispatch(
            new UserRequestQuery($this->converter->convert($criteria))
        );

        return new JsonResponse(PaginationResponseDTO::createFromPagination(
            $pagination,
            static fn (array $items) => UserRequestResponseDTO::createList($items)
        ));
    }

    #[Route('/api/users/projects', name: 'user.getAllProjects', methods: ['GET'])]
    public function getAllProjects(RequestCriteriaDTO $criteria): JsonResponse
    {
        /** @var Pagination $pagination */
        $pagination = $this->queryBus->dispatch(
            new UserProjectQuery($this->converter->convert($criteria))
        );

        return new JsonResponse(PaginationResponseDTO::createFromPagination(
            $pagination,
            static fn (array $items) => ProjectListResponseDTO::createList($items)
        ));
    }
}
