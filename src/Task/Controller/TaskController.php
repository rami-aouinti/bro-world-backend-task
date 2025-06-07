<?php

declare(strict_types=1);

namespace App\Task\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use App\Projects\Application\Command\ActivateTaskCommand;
use App\Projects\Application\Command\ChangeTaskInformationCommand;
use App\Projects\Application\Command\CloseTaskCommand;
use App\Projects\Application\Command\CreateTaskLinkCommand;
use App\Projects\Application\Command\DeleteTaskLinkCommand;
use App\Projects\Infrastructure\Service\DTO\TaskInformationDTO;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class TaskController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
#[Route('/api/tasks', name: 'task.')]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
final readonly class TaskController
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {
    }

    #[Route('/{id}/', name: 'update', methods: ['PATCH'])]
    public function update(string $id, TaskInformationDTO $dto): JsonResponse
    {
        $command = new ChangeTaskInformationCommand(
            $id,
            $dto->name,
            $dto->brief,
            $dto->description,
            $dto->startDate,
            $dto->finishDate,
            $dto->version
        );

        $version = $this->commandBus->dispatch($command);

        return new JsonResponse([
            'version' => $version,
        ]);
    }

    #[Route('/{id}/activate/', name: 'activate', methods: ['PATCH'])]
    public function activate(string $id): JsonResponse
    {
        $command = new ActivateTaskCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/{id}/close/', name: 'close', methods: ['PATCH'])]
    public function close(string $id): JsonResponse
    {
        $command = new CloseTaskCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/{id}/links/{linkedTaskId}/', name: 'createLink', methods: ['POST'])]
    public function createLink(string $id, string $linkedTaskId): JsonResponse
    {
        $command = new CreateTaskLinkCommand($id, $linkedTaskId);

        $this->commandBus->dispatch($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    #[Route('/{id}/links/{linkedTaskId}/', name: 'deleteLink', methods: ['DELETE'])]
    public function deleteLink(string $id, string $linkedTaskId): JsonResponse
    {
        $command = new DeleteTaskLinkCommand($id, $linkedTaskId);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }
}
