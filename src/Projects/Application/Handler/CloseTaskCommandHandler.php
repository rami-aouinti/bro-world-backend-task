<?php

declare(strict_types=1);

namespace App\Projects\Application\Handler;

use App\Projects\Application\Command\CloseTaskCommand;
use App\Projects\Application\Service\ProjectFinderInterface;
use App\Projects\Application\Service\TaskFinderInterface;
use App\Projects\Domain\Repository\TaskRepositoryInterface;
use App\Projects\Domain\ValueObject\TaskId;
use App\General\Application\Bus\Command\CommandHandlerInterface;
use App\General\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Application\Service\AuthenticatorServiceInterface;

final readonly class CloseTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface $repository,
        private TaskFinderInterface $finder,
        private ProjectFinderInterface $projectFinder,
        private AuthenticatorServiceInterface $authenticator,
        private IntegrationEventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CloseTaskCommand $command): void
    {
        $authUserId = $this->authenticator->getUserId();
        $task = $this->finder->find(new TaskId($command->id));
        $project = $this->projectFinder->find($task->getProjectId());

        $project->closeTask($task, $authUserId);

        $this->repository->save($task);
        $this->eventBus->dispatch($task->releaseEvents());
    }
}
