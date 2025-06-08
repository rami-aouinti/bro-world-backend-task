<?php

declare(strict_types=1);

namespace App\Projects\Application\Subscriber;

use App\Projects\Application\Service\ProjectFinderInterface;
use App\Projects\Domain\Event\TaskWasCreatedEvent;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\TaskId;
use App\Shared\Application\Bus\Event\DomainEventSubscriberInterface;
use App\Shared\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Domain\ValueObject\UserId;

/**
 * Class CreateProjectTaskOnTaskCreated
 *
 * @package App\Projects\Application\Subscriber
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class CreateProjectTaskOnTaskCreated implements DomainEventSubscriberInterface
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
        private ProjectFinderInterface $finder,
        private IntegrationEventBusInterface $eventBus
    ) {
    }

    public function __invoke(TaskWasCreatedEvent $event): void
    {
        $project = $this->finder->find(new ProjectId($event->projectId));

        $project->addProjectTask(
            new TaskId($event->getAggregateId()),
            new UserId($event->ownerId)
        );

        $this->repository->save($project);
        $this->eventBus->dispatch($project->releaseEvents());
    }
}
