<?php

declare(strict_types=1);

namespace App\Projects\Application\Subscriber;

use App\Projects\Application\Service\TaskFinderInterface;
use App\Projects\Domain\Event\ProjectTaskWasClosedEvent;
use App\Projects\Domain\Repository\TaskRepositoryInterface;
use App\Projects\Domain\ValueObject\TaskId;
use App\General\Application\Bus\Event\DomainEventSubscriberInterface;
use App\General\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Domain\ValueObject\UserId;

/**
 * Class CloseTaskOnProjectTaskClosed
 *
 * @package App\Projects\Application\Subscriber
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class CloseTaskOnProjectTaskClosed implements DomainEventSubscriberInterface
{
    public function __construct(
        private TaskRepositoryInterface $repository,
        private TaskFinderInterface $finder,
        private IntegrationEventBusInterface $eventBus
    ) {
    }

    public function __invoke(ProjectTaskWasClosedEvent $event): void
    {
        $task = $this->finder->find(new TaskId($event->taskId));

        $task->closeAsNeeded(new UserId($event->getPerformerId()));

        $this->repository->save($task);
        $this->eventBus->dispatch($task->releaseEvents());
    }
}
