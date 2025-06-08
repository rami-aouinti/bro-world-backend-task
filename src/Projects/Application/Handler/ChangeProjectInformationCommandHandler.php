<?php

declare(strict_types=1);

namespace App\Projects\Application\Handler;

use App\Projects\Application\Command\ChangeProjectInformationCommand;
use App\Projects\Application\Service\ProjectFinderInterface;
use App\Projects\Application\Service\ProjectSaverInterface;
use App\Projects\Domain\ValueObject\ProjectDescription;
use App\Projects\Domain\ValueObject\ProjectFinishDate;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\ProjectInformation;
use App\Projects\Domain\ValueObject\ProjectName;
use App\General\Application\Bus\Command\CommandHandlerInterface;
use App\General\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Application\Service\AuthenticatorServiceInterface;

final readonly class ChangeProjectInformationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProjectSaverInterface $saver,
        private ProjectFinderInterface $finder,
        private AuthenticatorServiceInterface $authenticator,
        private IntegrationEventBusInterface $eventBus,
    ) {
    }

    public function __invoke(ChangeProjectInformationCommand $command): int
    {
        $authUserId = $this->authenticator->getUserId();
        $project = $this->finder->find(new ProjectId($command->id));

        $project->changeInformation(
            new ProjectInformation(
                new ProjectName($command->name),
                new ProjectDescription($command->description),
                new ProjectFinishDate($command->finishDate),
            ),
            $authUserId
        );

        $version = (int) $command->version;

        $events = $project->releaseEvents();
        if (0 !== count($events)) {
            $version = $this->saver->save($project, $version);
            $this->eventBus->dispatch($events, $version);
        }

        return $version;
    }
}
