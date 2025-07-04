<?php

declare(strict_types=1);

namespace App\Projects\Domain\Entity;

use App\General\Domain\Aggregate\AggregateRoot;
use App\General\Domain\Equatable;
use App\General\Domain\ValueObject\UserId;
use App\Projects\Domain\Collection\ParticipantCollection;
use App\Projects\Domain\Collection\ProjectTaskCollection;
use App\Projects\Domain\Collection\RequestCollection;
use App\Projects\Domain\Event\ProjectInformationWasChangedEvent;
use App\Projects\Domain\Event\ProjectOwnerWasChangedEvent;
use App\Projects\Domain\Event\ProjectParticipantWasAddedEvent;
use App\Projects\Domain\Event\ProjectParticipantWasRemovedEvent;
use App\Projects\Domain\Event\ProjectStatusWasChangedEvent;
use App\Projects\Domain\Event\ProjectTaskFinishDateWasChangedEvent;
use App\Projects\Domain\Event\ProjectTaskWasClosedEvent;
use App\Projects\Domain\Event\ProjectTaskWasCreatedEvent;
use App\Projects\Domain\Event\ProjectWasCreatedEvent;
use App\Projects\Domain\Event\RequestStatusWasChangedEvent;
use App\Projects\Domain\Event\RequestWasCreatedEvent;
use App\Projects\Domain\Exception\ProjectUserDoesNotExistException;
use App\Projects\Domain\Exception\RequestDoesNotExistException;
use App\Projects\Domain\Exception\UserIsNotTaskOwnerException;
use App\Projects\Domain\ValueObject\ActiveProjectStatus;
use App\Projects\Domain\ValueObject\ClosedProjectStatus;
use App\Projects\Domain\ValueObject\ConfirmedRequestStatus;
use App\Projects\Domain\ValueObject\Participant;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\ProjectInformation;
use App\Projects\Domain\ValueObject\ProjectOwner;
use App\Projects\Domain\ValueObject\ProjectStatus;
use App\Projects\Domain\ValueObject\ProjectTask;
use App\Projects\Domain\ValueObject\RejectedRequestStatus;
use App\Projects\Domain\ValueObject\RequestId;
use App\Projects\Domain\ValueObject\RequestStatus;
use App\Projects\Domain\ValueObject\TaskId;
use App\Projects\Domain\ValueObject\TaskInformation;
use App\Projects\Domain\ValueObject\TaskOwner;

/**
 * Class Project
 *
 * @package App\Projects\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class Project extends AggregateRoot
{
    private function __construct(
        private readonly ProjectId $id,
        private ProjectInformation $information,
        private ProjectStatus $status,
        private ProjectOwner $owner,
        private readonly ParticipantCollection $participants,
        private readonly RequestCollection $requests,
        private readonly ProjectTaskCollection $tasks
    ) {
    }

    public static function create(
        ProjectId $id,
        ProjectInformation $information,
        ProjectOwner $owner
    ): self {
        $status = new ActiveProjectStatus();
        $project = new self(
            $id,
            $information,
            $status,
            $owner,
            new ParticipantCollection(),
            new RequestCollection(),
            new ProjectTaskCollection()
        );

        $project->registerEvent(new ProjectWasCreatedEvent(
            $id->value,
            $information->name->value,
            $information->description->value,
            $information->finishDate->getValue(),
            (string) $status->getScalar(),
            $owner->id->value,
            $owner->id->value
        ));

        return $project;
    }

    public function changeInformation(
        ProjectInformation $information,
        UserId $currentUserId
    ): void {
        $this->status->ensureAllowsModification();
        $this->owner->ensureUserIsOwner($currentUserId);

        if (!$this->information->equals($information)) {
            if (!$information->finishDate->equals($this->information->finishDate)) {
                /** @var ProjectTask $task */
                foreach ($this->tasks->getItems() as $task) {
                    $this->registerEvent(new ProjectTaskFinishDateWasChangedEvent(
                        $this->id->value,
                        $task->taskId->value,
                        $information->finishDate->getValue(),
                        $currentUserId->value
                    ));
                }
            }
            $this->information = $information;

            $this->registerEvent(new ProjectInformationWasChangedEvent(
                $this->id->value,
                $information->name->value,
                $information->description->value,
                $information->finishDate->getValue(),
                $currentUserId->value
            ));
        }
    }

    public function activate(UserId $currentUserId): void
    {
        $this->changeStatus(new ActiveProjectStatus(), $currentUserId);
    }

    public function close(UserId $currentUserId): void
    {
        $this->changeStatus(new ClosedProjectStatus(), $currentUserId);
        /** @var ProjectTask $task */
        foreach ($this->tasks->getItems() as $task) {
            $this->registerEvent(new ProjectTaskWasClosedEvent(
                $this->id->value,
                $task->taskId->value,
                $currentUserId->value
            ));
        }
    }

    public function changeOwner(ProjectOwner $owner, UserId $currentUserId): void
    {
        $this->status->ensureAllowsModification();
        $this->owner->ensureUserIsOwner($currentUserId);

        $this->owner->ensureUserIsNotOwner($owner->id);
        $this->participants->ensureUserIsParticipant($owner->id);

        /** @var Request $pendingRequest */
        $pendingRequest = $this->requests->findFirst(function (string $key, Request $request) use ($owner) {
            return $request->isPendingForUser($owner->id);
        });
        if ($pendingRequest !== null) {
            $this->rejectRequest($pendingRequest->getId(), $currentUserId);
        }

        // Remove new owner from participants
        $this->removeParticipantInner($owner->id, $currentUserId);

        $oldOwner = $this->owner;
        $this->owner = $owner;
        $this->registerEvent(new ProjectOwnerWasChangedEvent(
            $this->id->value,
            $this->owner->id->value,
            $currentUserId->value
        ));

        // Add old owner to participants
        $this->addParticipant($oldOwner->id, $currentUserId);
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->id->equals($this->id)
            && $other->information->equals($this->information)
            && $other->status->equals($this->status)
            && $other->owner->equals($this->owner);
    }

    public function removeParticipant(UserId $participantId, UserId $currentUserId): void
    {
        $this->owner->ensureUserIsOwner($currentUserId);
        $this->tasks->ensureUserDoesNotHaveTask($participantId, $this->id);
        $this->removeParticipantInner($participantId, $currentUserId);
    }

    public function leaveProject(UserId $participantId): void
    {
        $this->tasks->ensureUserDoesNotHaveTask($participantId, $this->id);
        $this->removeParticipantInner($participantId, $participantId);
    }

    public function createRequest(RequestId $id, UserId $currentUserId): Request
    {
        $this->status->ensureAllowsModification();
        $this->owner->ensureUserIsNotOwner($currentUserId);
        $this->participants->ensureUserIsNotParticipant($currentUserId);
        $this->requests->ensureUserDoesNotHavePendingRequest($currentUserId, $this->id);

        $request = Request::create($id, $this->id, $currentUserId);

        $this->requests->addOrUpdateElement($request);

        $this->registerEvent(new RequestWasCreatedEvent(
            $this->id->value,
            $id->value,
            $currentUserId->value,
            (string) $request->getStatus()->getScalar(),
            $request->getChangeDate()->getValue(),
            $currentUserId->value
        ));

        return $request;
    }

    public function confirmRequest(RequestId $id, UserId $currentUserId): void
    {
        $request = $this->changeRequestStatus($id, new ConfirmedRequestStatus(), $currentUserId);
        $this->addParticipant($request->getUserId(), $currentUserId);
    }

    public function rejectRequest(RequestId $id, UserId $currentUserId): void
    {
        $this->changeRequestStatus($id, new RejectedRequestStatus(), $currentUserId);
    }

    public function createTask(
        TaskId $id,
        TaskInformation $information,
        TaskOwner $owner
    ): Task {
        $this->status->ensureAllowsModification();
        $this->ensureUserIsProjectUser($owner->id);
        $this->information->ensureIsFinishDateGreaterThanTaskDates(
            $information->startDate,
            $information->finishDate
        );

        return Task::create($id, $this->id, $information, $owner);
    }

    public function changeTaskInformation(
        Task $task,
        TaskInformation $information,
        UserId $currentUserId
    ): void {
        $this->status->ensureAllowsModification();
        $this->tasks->ensureProjectTaskExists($task->getId());
        $this->information->ensureIsFinishDateGreaterThanTaskDates(
            $information->startDate,
            $information->finishDate
        );

        try {
            $task->changeInformation($information, $currentUserId);
        } catch (UserIsNotTaskOwnerException $e) {
            if (!$this->owner->userIsOwner($currentUserId)) {
                throw $e;
            }
        }
    }

    public function activateTask(Task $task, UserId $currentUserId): void
    {
        $this->status->ensureAllowsModification();
        $this->tasks->ensureProjectTaskExists($task->getId());

        try {
            $task->activate($currentUserId);
        } catch (UserIsNotTaskOwnerException $e) {
            if (!$this->owner->userIsOwner($currentUserId)) {
                throw $e;
            }
        }
    }

    public function closeTask(Task $task, UserId $currentUserId): void
    {
        $this->status->ensureAllowsModification();
        $this->tasks->ensureProjectTaskExists($task->getId());

        try {
            $task->close($currentUserId);
        } catch (UserIsNotTaskOwnerException $e) {
            if (!$this->owner->userIsOwner($currentUserId)) {
                throw $e;
            }
        }
    }

    public function createTaskLink(Task $task, TaskId $linkedTaskId, UserId $currentUserId): void
    {
        $this->status->ensureAllowsModification();
        $this->tasks->ensureProjectTaskExists($task->getId());
        $this->tasks->ensureProjectTaskExists($linkedTaskId);

        try {
            $task->createLink($linkedTaskId, $currentUserId);
        } catch (UserIsNotTaskOwnerException $e) {
            if (!$this->owner->userIsOwner($currentUserId)) {
                throw $e;
            }
        }
    }

    public function deleteTaskLink(Task $task, TaskId $linkedTaskId, UserId $currentUserId): void
    {
        $this->status->ensureAllowsModification();
        $this->tasks->ensureProjectTaskExists($task->getId());
        $this->tasks->ensureProjectTaskExists($linkedTaskId);

        try {
            $task->deleteLink($linkedTaskId, $currentUserId);
        } catch (UserIsNotTaskOwnerException $e) {
            if (!$this->owner->userIsOwner($currentUserId)) {
                throw $e;
            }
        }
    }

    public function addProjectTask(TaskId $taskId, UserId $userId): void
    {
        $this->status->ensureAllowsModification();
        $this->ensureUserIsProjectUser($userId);

        $this->tasks->addOrUpdateElement(new ProjectTask(
            $this->id,
            $taskId,
            $userId
        ));

        $this->registerEvent(new ProjectTaskWasCreatedEvent(
            $this->id->value,
            $taskId->value,
            $userId->value,
            $this->owner->id->value
        ));
    }

    public function getId(): ProjectId
    {
        return $this->id;
    }

    private function changeStatus(ProjectStatus $status, UserId $currentUserId): void
    {
        $this->status->ensureCanBeChangedTo($status);
        $this->owner->ensureUserIsOwner($currentUserId);

        $this->status = $status;

        $this->registerEvent(new ProjectStatusWasChangedEvent(
            $this->id->value,
            (string) $status->getScalar(),
            $currentUserId->value
        ));
    }

    private function removeParticipantInner(UserId $participantId, UserId $performerId): void
    {
        $this->status->ensureAllowsModification();
        $this->participants->ensureUserIsParticipant($participantId);

        $this->participants->remove($participantId->value);

        $this->registerEvent(new ProjectParticipantWasRemovedEvent(
            $this->id->value,
            $participantId->value,
            $performerId->value
        ));
    }

    private function changeRequestStatus(
        RequestId $id,
        RequestStatus $status,
        UserId $currentUserId
    ): Request {
        $this->status->ensureAllowsModification();
        $this->owner->ensureUserIsOwner($currentUserId);
        if (!$this->requests->exists($id->value)) {
            throw new RequestDoesNotExistException($id->value, $this->id->value);
        }

        /** @var Request $request */
        $request = $this->requests->get($id->value);
        $request->changeStatus($status);

        $this->registerEvent(new RequestStatusWasChangedEvent(
            $this->id->value,
            $request->getId()->value,
            $request->getUserId()->value,
            (string) $request->getStatus()->getScalar(),
            $request->getChangeDate()->getValue(),
            $currentUserId->value
        ));

        return $request;
    }

    private function addParticipant(UserId $participantId, UserId $currentUserId): void
    {
        $this->participants->addOrUpdateElement(new Participant(
            $this->id,
            $participantId
        ));

        $this->registerEvent(new ProjectParticipantWasAddedEvent(
            $this->id->value,
            $participantId->value,
            $currentUserId->value
        ));
    }

    private function ensureUserIsProjectUser(UserId $userId): void
    {
        if (!$this->owner->userIsOwner($userId) && !$this->participants->exists($userId->value)) {
            throw new ProjectUserDoesNotExistException($userId->value);
        }
    }
}
