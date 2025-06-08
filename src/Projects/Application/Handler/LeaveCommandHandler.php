<?php

declare(strict_types=1);

namespace App\Projects\Application\Handler;

use App\Projects\Application\Command\LeaveCommand;
use App\Projects\Application\Service\ProjectFinderInterface;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Projects\Domain\ValueObject\ProjectId;
use App\General\Application\Bus\Command\CommandHandlerInterface;
use App\General\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Application\Service\AuthenticatorServiceInterface;

final readonly class LeaveCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
        private ProjectFinderInterface $finder,
        private AuthenticatorServiceInterface $authenticator,
        private IntegrationEventBusInterface $eventBus,
    ) {
    }

    public function __invoke(LeaveCommand $command): void
    {
        $authUserId = $this->authenticator->getUserId();
        $project = $this->finder->find(new ProjectId($command->projectId));

        $project->leaveProject($authUserId);

        $this->repository->save($project);
        $this->eventBus->dispatch($project->releaseEvents());
    }
}
