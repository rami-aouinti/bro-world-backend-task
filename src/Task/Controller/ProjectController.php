<?php

declare(strict_types=1);

namespace App\Task\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
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
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Application\Service\UuidGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class ProjectController
 *
 * @package App\Task\Controller
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsController]
#[Route('/api/projects', name: 'project.')]
final readonly class ProjectController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(ProjectInformationDTO $dto): JsonResponse
    {
        $command = new CreateProjectCommand(
            $this->uuidGenerator->generate(),
            $dto->name,
            $dto->description,
            $dto->finishDate
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->id], Response::HTTP_CREATED);
    }

    #[Route('/{id}/', name: 'update', methods: ['PATCH'])]
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

    #[Route('/{id}/activate/', name: 'activate', methods: ['PATCH'])]
    public function activate(string $id): JsonResponse
    {
        $command = new ActivateProjectCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/{id}/close/', name: 'close', methods: ['PATCH'])]
    public function close(string $id): JsonResponse
    {
        $command = new CloseProjectCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/{id}/change-owner/{ownerId}/', name: 'changeOwner', methods: ['PATCH'])]
    public function changeOwner(string $id, string $ownerId): JsonResponse
    {
        $command = new ChangeProjectOwnerCommand($id, $ownerId);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/{id}/participants/{participantId}/', name: 'removeParticipant', methods: ['DELETE'])]
    public function removeParticipant(string $id, string $participantId): JsonResponse
    {
        $command = new RemoveParticipantCommand($id, $participantId);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/{id}/leave/', name: 'leave', methods: ['PATCH'])]
    public function leave(string $id): JsonResponse
    {
        $command = new LeaveCommand($id);

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/{id}/requests/', name: 'createRequest', methods: ['POST'])]
    public function createRequest(string $id): JsonResponse
    {
        $command = new CreateRequestCommand(
            $this->uuidGenerator->generate(),
            $id
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->id], Response::HTTP_CREATED);
    }

    #[Route('/{id}/requests/{requestId}/confirm/', name: 'confirmRequest', methods: ['PATCH'])]
    public function confirmRequest(string $id, string $requestId): JsonResponse
    {
        $command = new ConfirmRequestCommand(
            $requestId,
            $id
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/{id}/requests/{requestId}/reject/', name: 'rejectRequest', methods: ['PATCH'])]
    public function rejectRequest(string $id, string $requestId): JsonResponse
    {
        $command = new RejectRequestCommand(
            $requestId,
            $id
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse();
    }

    #[Route('/{id}/tasks/', name: 'createTask', methods: ['POST'])]
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
