<?php

declare(strict_types=1);

namespace App\Projects\Domain\Collection;

use App\General\Domain\Collection\ManagedCollection;
use App\General\Domain\ValueObject\UserId;
use App\Projects\Domain\Exception\ProjectTaskDoesNotExistException;
use App\Projects\Domain\Exception\ProjectUserHasTaskException;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\ProjectTask;
use App\Projects\Domain\ValueObject\TaskId;

/**
 * Class ProjectTaskCollection
 *
 * @package App\Projects\Domain\Collection
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectTaskCollection extends ManagedCollection
{
    public function ensureUserDoesNotHaveTask(UserId $userId, ProjectId $projectId): void
    {
        /** @var ProjectTask $task */
        foreach ($this->getItems() as $task) {
            if ($task->userId->equals($userId)) {
                throw new ProjectUserHasTaskException($userId->value, $projectId->value);
            }
        }
    }

    public function ensureProjectTaskExists(TaskId $id): void
    {
        if (!$this->exists($id->value)) {
            throw new ProjectTaskDoesNotExistException($id->value);
        }
    }

    /**
     * @return class-string
     */
    protected function supportClass(): string
    {
        return ProjectTask::class;
    }
}
