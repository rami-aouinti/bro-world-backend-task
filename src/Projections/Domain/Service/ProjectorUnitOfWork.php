<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service;

use App\General\Domain\Hashable;

/**
 * Class ProjectorUnitOfWork
 *
 * @package App\Projections\Domain\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectorUnitOfWork
{
    /**
     * @var Hashable[]
     */
    private array $projections = [];

    private array $deletedProjections = [];

    /**
     * @return Hashable[]
     */
    public function getProjections(): array
    {
        return array_filter($this->projections, function ($value) {
            return !isset($this->deletedProjections[$value->getHash()]);
        });
    }

    /**
     * @return Hashable[]
     */
    public function getDeletedProjections(): array
    {
        return array_filter($this->projections, function ($value) {
            return isset($this->deletedProjections[$value->getHash()]);
        });
    }

    public function flush(): void
    {
        $this->projections = [];
        $this->deletedProjections = [];
    }

    public function findProjection(string $hash): ?Hashable
    {
        return $this->projections[$hash] ?? null;
    }

    /**
     * @return Hashable[]
     */
    public function findProjections(callable $callback): array
    {
        return array_filter($this->projections, static function ($value) use ($callback) {
            return $callback($value);
        });
    }

    /**
     * @param Hashable[] $projections
     */
    public function loadProjections(array $projections): void
    {
        foreach ($projections as $projection) {
            $this->loadProjection($projection);
        }
    }

    public function createProjection(Hashable $projection): void
    {
        $this->loadProjection($projection);
        $this->undeleteProjection($projection);
    }

    public function loadProjection(Hashable $projection): void
    {
        $this->projections[$projection->getHash()] = $projection;
    }

    public function deleteProjection(?Hashable $projection): void
    {
        if ($projection === null) {
            return;
        }

        $this->loadProjection($projection);

        $this->deletedProjections[$projection->getHash()] = $projection->getHash();
    }

    public function undeleteProjection(Hashable $projection): void
    {
        unset($this->deletedProjections[$projection->getHash()]);
    }
}
