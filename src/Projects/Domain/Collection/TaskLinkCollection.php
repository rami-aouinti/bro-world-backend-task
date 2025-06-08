<?php

declare(strict_types=1);

namespace App\Projects\Domain\Collection;

use App\Projects\Domain\Exception\TaskLinkAlreadyExistsException;
use App\Projects\Domain\Exception\TaskLinkDoesNotExistException;
use App\Projects\Domain\ValueObject\TaskLink;
use App\Shared\Domain\Collection\ManagedCollection;

/**
 * Class TaskLinkCollection
 *
 * @package App\Projects\Domain\Collection
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskLinkCollection extends ManagedCollection
{
    public function ensureTaskLinkExists(TaskLink $link): void
    {
        if (!$this->exists($link->getHash())) {
            throw new TaskLinkDoesNotExistException($link->linkedTaskId->value, $link->taskId->value);
        }
    }

    public function ensureTaskLinkDoesNotExist(TaskLink $link): void
    {
        if ($this->exists($link->getHash())) {
            throw new TaskLinkAlreadyExistsException($link->linkedTaskId->value, $link->taskId->value);
        }
    }

    /**
     * @return class-string
     */
    protected function supportClass(): string
    {
        return TaskLink::class;
    }
}
