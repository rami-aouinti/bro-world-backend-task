<?php

declare(strict_types=1);

namespace App\Projects\Application\Subscriber;

use App\Projects\Application\Service\TaskFinderInterface;
use App\Projects\Domain\Event\TaskLinkWasDeleted;
use App\Projects\Domain\Repository\TaskRepositoryInterface;
use App\Projects\Domain\ValueObject\TaskId;
use App\Shared\Application\Bus\Event\DomainEventSubscriberInterface;
use App\Shared\Application\Bus\Event\IntegrationEventBusInterface;
use App\Shared\Domain\ValueObject\UserId;

final readonly class DeleteBackLinkOnTaskLinkDeleted implements DomainEventSubscriberInterface
{
    public function __construct(
        private TaskRepositoryInterface $repository,
        private TaskFinderInterface $finder,
        private IntegrationEventBusInterface $eventBus
    ) {
    }

    public function __invoke(TaskLinkWasDeleted $event): void
    {
        $task = $this->finder->find(new TaskId($event->linkedTaskId));

        $task->deleteBackLink(
            new TaskId($event->getAggregateId()),
            new UserId($event->getPerformerId())
        );

        $this->repository->save($task);
        $this->eventBus->dispatch($task->releaseEvents());
    }
}
