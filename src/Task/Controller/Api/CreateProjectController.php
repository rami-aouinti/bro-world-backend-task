<?php

declare(strict_types=1);

namespace App\Task\Controller\Api;

use App\General\Infrastructure\ValueObject\SymfonyUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use App\Projects\Application\Command\CreateProjectCommand;
use App\Projects\Infrastructure\Service\DTO\ProjectInformationDTO;
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
#[Route('/projects', name: 'platform_project.')]
final readonly class CreateProjectController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    #[Route('/', name: 'create', methods: [Request::METHOD_POST])]
    public function __invoke(SymfonyUser $user, ProjectInformationDTO $dto): JsonResponse
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
}
