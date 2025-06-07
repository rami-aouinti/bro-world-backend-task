<?php

declare(strict_types=1);

namespace App\Task\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use App\Projections\Application\Query\UserProfileQuery;
use App\Projections\Application\Query\UserProjectQuery;
use App\Projections\Application\Query\UserRequestQuery;
use App\Projections\Infrastructure\DTO\ProjectListResponseDTO;
use App\Projections\Infrastructure\DTO\UserRequestResponseDTO;
use App\Projections\Infrastructure\DTO\UserResponseDTO;
use App\Shared\Application\Bus\Query\QueryBusInterface;
use App\Shared\Application\Paginator\Pagination;
use App\Shared\Infrastructure\Criteria\QueryCriteriaFromRequestConverterInterface;
use App\Shared\Infrastructure\Criteria\RequestCriteriaDTO;
use App\Shared\Infrastructure\Paginator\PaginationResponseDTO;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class UserProjectionController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
#[Route('/api/users', name: 'user.')]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
final readonly class UserProjectionController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private QueryCriteriaFromRequestConverterInterface $converter
    ) {
    }

    #[Route('/', name: 'getInfo', methods: ['GET'])]
    public function getInfo(): JsonResponse
    {
        $user = $this->queryBus->dispatch(new UserProfileQuery());

        return new JsonResponse(UserResponseDTO::create($user));
    }

    #[Route('/requests/', name: 'getAllRequests', methods: ['GET'])]
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

    #[Route('/projects/', name: 'getAllProjects', methods: ['GET'])]
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
