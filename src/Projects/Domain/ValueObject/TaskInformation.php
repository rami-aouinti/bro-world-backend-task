<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\TaskStartDateIsGreaterThanFinishDateException;
use App\Shared\Domain\Equatable;
use App\Shared\Domain\ValueObject\DateTime;

/**
 * Class TaskInformation
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskInformation implements Equatable
{
    public function __construct(
        public TaskName $name,
        public TaskBrief $brief,
        public TaskDescription $description,
        public TaskStartDate $startDate,
        public TaskFinishDate $finishDate
    ) {
    }

    public function ensureFinishDateGreaterOrEqualStartDate(): void
    {
        if ($this->startDate->isGreaterThan($this->finishDate)) {
            throw new TaskStartDateIsGreaterThanFinishDateException($this->startDate->getValue(), $this->finishDate->getValue());
        }
    }

    public function limitDates(DateTime $date): self
    {
        $newStartDate = $this->startDate;
        if ($newStartDate->isGreaterThan($date)) {
            $newStartDate = new TaskStartDate($date->getValue());
        }
        $newFinishDate = $this->finishDate;
        if ($newFinishDate->isGreaterThan($date)) {
            $newFinishDate = new TaskFinishDate($date->getValue());
        }

        return new TaskInformation(
            $this->name,
            $this->brief,
            $this->description,
            $newStartDate,
            $newFinishDate,
        );
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->name->equals($this->name)
            && $other->brief->equals($this->brief)
            && $other->description->equals($this->description)
            && $other->startDate->equals($this->startDate)
            && $other->finishDate->equals($this->finishDate);
    }
}
