<?php

declare(strict_types=1);

namespace App\Projects\Application\Subscriber;

use App\Projects\Application\Service\TaskFinderInterface;
use App\Projects\Domain\Event\TaskLinkWasCreated;
use App\Projects\Domain\Repository\TaskRepositoryInterface;
use App\Projects\Domain\ValueObject\TaskId;
use App\General\Application\Bus\Event\DomainEventSubscriberInterface;
use App\General\Application\Bus\Event\IntegrationEventBusInterface;
use App\General\Domain\ValueObject\UserId;

/**
 * Class CreateBackLinkOnTaskLinkCreated
 *
 * @package App\Projects\Application\Subscriber
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class CreateBackLinkOnTaskLinkCreated implements DomainEventSubscriberInterface
{
    public function __construct(
        private TaskRepositoryInterface $repository,
        private TaskFinderInterface $finder,
        private IntegrationEventBusInterface $eventBus
    ) {
    }

    public function __invoke(TaskLinkWasCreated $event): void
    {
        $task = $this->finder->find(new TaskId($event->linkedTaskId));

        $task->createBackLink(
            new TaskId($event->getAggregateId()),
            new UserId($event->getPerformerId())
        );

        $this->repository->save($task);
        $this->eventBus->dispatch($task->releaseEvents());
    }
}
