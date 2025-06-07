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

/**
 * Class UserProjectionController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
#[Route('/api/users', name: 'user.')]
final readonly class UserProjectionController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private QueryCriteriaFromRequestConverterInterface $converter
    ) {
    }

    #[Route('/', name: 'getInfo', methods: ['GET'])]
    #[OA\Get(
        description: 'Get info about own profile',
        tags: [
            'user',
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Profile info',
                content: new OA\JsonContent(
                    ref: new Model(type: UserResponseDTO::class)
                )
            ),
            new OA\Response(ref: '#components/responses/generic401', response: '401'),
            new OA\Response(ref: '#components/responses/generic404', response: '404'),
        ]
    )]
    public function getInfo(): JsonResponse
    {
        $user = $this->queryBus->dispatch(new UserProfileQuery());

        return new JsonResponse(UserResponseDTO::create($user));
    }

    #[Route('/requests/', name: 'getAllRequests', methods: ['GET'])]
    #[OA\Get(
        description: 'Get all user requests',
        tags: [
            'user',
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'List of user requests with pagination',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(
                            ref: '#components/schemas/pagination'
                        ),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'items',
                                    type: 'array',
                                    items: new OA\Items(
                                        ref: new Model(type: UserRequestResponseDTO::class)
                                    )
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(ref: '#components/responses/generic401', response: '401'),
            new OA\Response(ref: '#components/responses/generic403', response: '403'),
            new OA\Response(ref: '#components/responses/generic404', response: '404'),
        ]
    )]
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
    #[OA\Get(
        description: 'Get all user projects',
        tags: [
            'user',
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'List of user projects with pagination',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(
                            ref: '#components/schemas/pagination'
                        ),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'items',
                                    type: 'array',
                                    items: new OA\Items(
                                        ref: new Model(type: ProjectListResponseDTO::class)
                                    )
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(ref: '#components/responses/generic401', response: '401'),
            new OA\Response(ref: '#components/responses/generic403', response: '403'),
            new OA\Response(ref: '#components/responses/generic404', response: '404'),
        ]
    )]
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
