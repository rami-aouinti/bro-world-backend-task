<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\General\Domain\Equatable;
use App\Projects\Domain\Exception\TaskFinishDateIsGreaterThanProjectFinishDateException;
use App\Projects\Domain\Exception\TaskStartDateIsGreaterThanProjectFinishDateException;

/**
 * Class ProjectInformation
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectInformation implements Equatable
{
    public function __construct(
        public ProjectName $name,
        public ProjectDescription $description,
        public ProjectFinishDate $finishDate,
    ) {
    }

    public function ensureIsFinishDateGreaterThanTaskDates(?TaskStartDate $startDate, ?TaskFinishDate $finishDate): void
    {
        if ($startDate !== null && $startDate->isGreaterThan($this->finishDate)) {
            throw new TaskStartDateIsGreaterThanProjectFinishDateException($this->finishDate->getValue(), $startDate->getValue())
            ;
        }
        if ($finishDate !== null && $finishDate->isGreaterThan($this->finishDate)) {
            throw new TaskFinishDateIsGreaterThanProjectFinishDateException($this->finishDate->getValue(), $finishDate->getValue());
        }
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->name->equals($this->name)
            && $other->description->equals($this->description)
            && $other->finishDate->equals($this->finishDate);
    }
}
