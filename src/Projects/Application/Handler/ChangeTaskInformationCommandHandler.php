<?php

declare(strict_types=1);

namespace App\Projects\Application\Handler;

use App\Projects\Application\Command\ChangeTaskInformationCommand;
use App\Projects\Application\Service\ProjectFinderInterface;
use App\Projects\Application\Service\TaskFinderInterface;
use App\Projects\Application\Service\TaskSaverInterface;
use App\Projects\Domain\ValueObject\TaskBrief;
use App\Projects\Domain\ValueObject\TaskDescription;
use App\Projects\Domain\ValueObject\TaskFinishDate;
use App\Projects\Domain\ValueObject\TaskId;
use App\Projects\Domain\ValueObject\TaskInformation;
use App\Projects\Domain\ValueObject\TaskName;
use App\Projects\Domain\ValueObject\TaskStartDate;
use App\General\Application\Bus\Command\CommandHandlerInterface;
use App\General\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Application\Service\AuthenticatorServiceInterface;

final readonly class ChangeTaskInformationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskSaverInterface $saver,
        private TaskFinderInterface $finder,
        private ProjectFinderInterface $projectFinder,
        private AuthenticatorServiceInterface $authenticator,
        private IntegrationEventBusInterface $eventBus,
    ) {
    }

    public function __invoke(ChangeTaskInformationCommand $command): int
    {
        $authUserId = $this->authenticator->getUserId();
        $task = $this->finder->find(new TaskId($command->id));
        $project = $this->projectFinder->find($task->getProjectId());

        $project->changeTaskInformation(
            $task,
            new TaskInformation(
                new TaskName($command->name),
                new TaskBrief($command->brief),
                new TaskDescription($command->description),
                new TaskStartDate($command->startDate),
                new TaskFinishDate($command->finishDate),
            ),
            $authUserId
        );

        $version = (int) $command->version;

        $events = $task->releaseEvents();
        if (0 !== count($events)) {
            $version = $this->saver->save($task, $version);
            $this->eventBus->dispatch($events, $version);
        }

        return $version;
    }
}
