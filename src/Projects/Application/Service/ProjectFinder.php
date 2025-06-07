<?php

declare(strict_types=1);

namespace App\Projects\Application\Service;

use App\Projects\Domain\Entity\Project;
use App\Projects\Domain\Exception\ProjectDoesNotExistException;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Projects\Domain\ValueObject\ProjectId;

final readonly class ProjectFinder implements ProjectFinderInterface
{
    public function __construct(private ProjectRepositoryInterface $repository)
    {
    }

    public function find(ProjectId $id): Project
    {
        $project = $this->repository->findById($id);
        if (null === $project) {
            throw new ProjectDoesNotExistException($id->value);
        }

        return $project;
    }
}
