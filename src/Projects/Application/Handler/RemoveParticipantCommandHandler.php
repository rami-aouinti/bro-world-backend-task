<?php

declare(strict_types=1);

namespace App\Projects\Application\Handler;

use App\Projects\Application\Command\RemoveParticipantCommand;
use App\Projects\Application\Service\ProjectFinderInterface;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Projects\Domain\ValueObject\ProjectId;
use App\General\Application\Bus\Command\CommandHandlerInterface;
use App\General\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Application\Service\AuthenticatorServiceInterface;
use App\General\Domain\ValueObject\UserId;

final readonly class RemoveParticipantCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
        private ProjectFinderInterface $finder,
        private AuthenticatorServiceInterface $authenticator,
        private IntegrationEventBusInterface $eventBus,
    ) {
    }

    public function __invoke(RemoveParticipantCommand $command): void
    {
        $authUserId = $this->authenticator->getUserId();
        $project = $this->finder->find(new ProjectId($command->projectId));

        $project->removeParticipant(
            new UserId($command->participantId),
            $authUserId
        );

        $this->repository->save($project);
        $this->eventBus->dispatch($project->releaseEvents());
    }
}
