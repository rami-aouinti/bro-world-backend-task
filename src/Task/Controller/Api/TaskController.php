<?php

declare(strict_types=1);

namespace App\Task\Controller\Api;

use App\General\Application\Bus\Command\CommandBusInterface;
use App\Projects\Application\Command\ActivateTaskCommand;
use App\Projects\Application\Command\ChangeTaskInformationCommand;
use App\Projects\Application\Command\CloseTaskCommand;
use App\Projects\Application\Command\CreateTaskLinkCommand;
use App\Projects\Application\Command\DeleteTaskLinkCommand;
use App\Projects\Infrastructure\Service\DTO\TaskInformationDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class TaskController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
final readonly class TaskController
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {
    }

    #[Route('/api/tasks/{id}', name: 'task.update', methods: ['PATCH'])]
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

    #[Route('/api/tasks/{id}/activate', name: 'task.activate', methods: ['PATCH'])]
    public function activate(string $id): JsonResponse
    {
        $command = new ActivateTaskCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/api/tasks/{id}/close', name: 'task.close', methods: ['PATCH'])]
    public function close(string $id): JsonResponse
    {
        $command = new CloseTaskCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/api/tasks/{id}/links/{linkedTaskId}', name: 'task.createLink', methods: ['POST'])]
    public function createLink(string $id, string $linkedTaskId): JsonResponse
    {
        $command = new CreateTaskLinkCommand($id, $linkedTaskId);

        $this->commandBus->dispatch($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    #[Route('/api/tasks/{id}/links/{linkedTaskId}', name: 'task.deleteLink', methods: ['DELETE'])]
    public function deleteLink(string $id, string $linkedTaskId): JsonResponse
    {
        $command = new DeleteTaskLinkCommand($id, $linkedTaskId);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }
}
