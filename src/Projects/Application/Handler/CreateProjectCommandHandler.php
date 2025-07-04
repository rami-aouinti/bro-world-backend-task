<?php

declare(strict_types=1);

namespace App\Projects\Application\Handler;

use App\Projects\Application\Command\CreateProjectCommand;
use App\Projects\Application\Service\ProjectSaverInterface;
use App\Projects\Domain\Entity\Project;
use App\Projects\Domain\ValueObject\ProjectDescription;
use App\Projects\Domain\ValueObject\ProjectFinishDate;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\ProjectInformation;
use App\Projects\Domain\ValueObject\ProjectName;
use App\Projects\Domain\ValueObject\ProjectOwner;
use App\General\Application\Bus\Command\CommandHandlerInterface;
use App\General\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Application\Service\AuthenticatorServiceInterface;

/**
 * Class CreateProjectCommandHandler
 *
 * @package App\Projects\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class CreateProjectCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProjectSaverInterface $saver,
        private AuthenticatorServiceInterface $authenticator,
        private IntegrationEventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateProjectCommand $command): void
    {
        $authUserId = $this->authenticator->getUserId();

        $project = Project::create(
            new ProjectId($command->id),
            new ProjectInformation(
                new ProjectName($command->name),
                new ProjectDescription($command->description),
                new ProjectFinishDate($command->finishDate)
            ),
            new ProjectOwner(
                $authUserId
            )
        );

        $newVersion = $this->saver->save($project, 0);
        $this->eventBus->dispatch($project->releaseEvents(), $newVersion);
    }
}
