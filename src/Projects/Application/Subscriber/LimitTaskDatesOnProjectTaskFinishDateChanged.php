<?php

declare(strict_types=1);

namespace App\Projects\Application\Subscriber;

use App\Projects\Application\Service\TaskFinderInterface;
use App\Projects\Domain\Event\ProjectTaskFinishDateWasChangedEvent;
use App\Projects\Domain\Repository\TaskRepositoryInterface;
use App\Projects\Domain\ValueObject\TaskId;
use App\Shared\Application\Bus\Event\DomainEventSubscriberInterface;
use App\Shared\Application\Bus\Event\IntegrationEventBusInterface;
use App\Shared\Domain\ValueObject\DateTime;
use App\General\Domain\ValueObject\UserId;

final readonly class LimitTaskDatesOnProjectTaskFinishDateChanged implements DomainEventSubscriberInterface
{
    public function __construct(
        private TaskRepositoryInterface $repository,
        private TaskFinderInterface $finder,
        private IntegrationEventBusInterface $eventBus
    ) {
    }

    public function __invoke(ProjectTaskFinishDateWasChangedEvent $event): void
    {
        $task = $this->finder->find(new TaskId($event->taskId));

        $task->limitDates(
            new DateTime($event->finishDate),
            new UserId($event->getPerformerId())
        );

        $this->repository->save($task);
        $this->eventBus->dispatch($task->releaseEvents());
    }
}
