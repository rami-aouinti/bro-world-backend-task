<?php

declare(strict_types=1);

namespace App\Projects\Application\Handler;

use App\Projects\Application\Command\CreateTaskCommand;
use App\Projects\Application\Service\ProjectFinderInterface;
use App\Projects\Application\Service\TaskSaverInterface;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\TaskBrief;
use App\Projects\Domain\ValueObject\TaskDescription;
use App\Projects\Domain\ValueObject\TaskFinishDate;
use App\Projects\Domain\ValueObject\TaskId;
use App\Projects\Domain\ValueObject\TaskInformation;
use App\Projects\Domain\ValueObject\TaskName;
use App\Projects\Domain\ValueObject\TaskOwner;
use App\Projects\Domain\ValueObject\TaskStartDate;
use App\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Shared\Application\Bus\Event\IntegrationEventBusInterface;
use App\Shared\Application\Service\AuthenticatorServiceInterface;

final readonly class CreateTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskSaverInterface $saver,
        private AuthenticatorServiceInterface $authenticator,
        private ProjectFinderInterface $finder,
        private IntegrationEventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateTaskCommand $command): void
    {
        $authUserId = $this->authenticator->getUserId();
        $project = $this->finder->find(new ProjectId($command->projectId));

        $task = $project->createTask(
            new TaskId($command->id),
            new TaskInformation(
                new TaskName($command->name),
                new TaskBrief($command->brief),
                new TaskDescription($command->description),
                new TaskStartDate($command->startDate),
                new TaskFinishDate($command->finishDate)
            ),
            new TaskOwner(
                $authUserId
            )
        );

        $newVersion = $this->saver->save($task, 0);
        $this->eventBus->dispatch($task->releaseEvents(), $newVersion);
    }
}
