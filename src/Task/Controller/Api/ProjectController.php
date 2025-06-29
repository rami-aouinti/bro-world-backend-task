<?php

declare(strict_types=1);

namespace App\Task\Controller\Api;

use App\General\Application\Bus\Command\CommandBusInterface;
use App\General\Application\Service\UuidGeneratorInterface;
use App\General\Infrastructure\ValueObject\SymfonyUser;
use App\Projects\Application\Command\ActivateProjectCommand;
use App\Projects\Application\Command\ChangeProjectInformationCommand;
use App\Projects\Application\Command\ChangeProjectOwnerCommand;
use App\Projects\Application\Command\CloseProjectCommand;
use App\Projects\Application\Command\ConfirmRequestCommand;
use App\Projects\Application\Command\CreateProjectCommand;
use App\Projects\Application\Command\CreateRequestCommand;
use App\Projects\Application\Command\CreateTaskCommand;
use App\Projects\Application\Command\LeaveCommand;
use App\Projects\Application\Command\RejectRequestCommand;
use App\Projects\Application\Command\RemoveParticipantCommand;
use App\Projects\Infrastructure\Service\DTO\ProjectInformationDTO;
use App\Projects\Infrastructure\Service\DTO\TaskInformationDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class ProjectController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
final readonly class ProjectController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    #[Route('/api/projects', name: 'projects.create', methods: ['POST'])]
    public function create(Request $request, SymfonyUser $symfonyUser): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = new ProjectInformationDTO(
            $data['name'] ?? '',
            $data['description'] ?? '',
            $data['finishDate'] ?? '',
            "1"
        );

        $command = new CreateProjectCommand(
            $this->uuidGenerator->generate(),
            $dto->name,
            $dto->description,
            $dto->finishDate
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->id], Response::HTTP_CREATED);
    }

    #[Route('/api/projects/{id}', name: 'projects.update', methods: ['PATCH'])]
    public function update(string $id, ProjectInformationDTO $dto): JsonResponse
    {
        $command = new ChangeProjectInformationCommand(
            $id,
            $dto->name,
            $dto->description,
            $dto->finishDate,
            $dto->version
        );

        $version = $this->commandBus->dispatch($command);

        return new JsonResponse([
            'version' => $version,
        ]);
    }

    #[Route('/api/projects/{id}/activate', name: 'projects.activate', methods: ['PATCH'])]
    public function activate(string $id): JsonResponse
    {
        $command = new ActivateProjectCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/api/projects/{id}/close', name: 'projects.close', methods: ['PATCH'])]
    public function close(string $id): JsonResponse
    {
        $command = new CloseProjectCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/api/projects/{id}/change-owner/{ownerId}', name: 'projects.changeOwner', methods: ['PATCH'])]
    public function changeOwner(string $id, string $ownerId): JsonResponse
    {
        $command = new ChangeProjectOwnerCommand($id, $ownerId);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/api/projects/{id}/participants/{participantId}', name: 'projects.removeParticipant', methods: ['DELETE'])]
    public function removeParticipant(string $id, string $participantId): JsonResponse
    {
        $command = new RemoveParticipantCommand($id, $participantId);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/api/projects/{id}/leave', name: 'projects.leave', methods: ['PATCH'])]
    public function leave(string $id): JsonResponse
    {
        $command = new LeaveCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/api/projects/{id}/requests', name: 'projects.createRequest', methods: ['POST'])]
    public function createRequest(string $id): JsonResponse
    {
        $command = new CreateRequestCommand(
            $this->uuidGenerator->generate(),
            $id
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->id], Response::HTTP_CREATED);
    }

    #[Route('/api/projects/{id}/requests/{requestId}/confirm', name: 'projects.confirmRequest', methods: ['PATCH'])]
    public function confirmRequest(string $id, string $requestId): JsonResponse
    {
        $command = new ConfirmRequestCommand(
            $requestId,
            $id
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/api/projects/{id}/requests/{requestId}/reject', name: 'projects.rejectRequest', methods: ['PATCH'])]
    public function rejectRequest(string $id, string $requestId): JsonResponse
    {
        $command = new RejectRequestCommand(
            $requestId,
            $id
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/api/projects/{id}/tasks', name: 'projects.createTask', methods: ['POST'])]
    public function createTask(string $id, TaskInformationDTO $dto): JsonResponse
    {
        $command = new CreateTaskCommand(
            $this->uuidGenerator->generate(),
            $id,
            $dto->name,
            $dto->brief,
            $dto->description,
            $dto->startDate,
            $dto->finishDate
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->id], Response::HTTP_CREATED);
    }
}
