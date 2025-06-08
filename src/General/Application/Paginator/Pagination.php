<?php

declare(strict_types=1);

namespace App\General\Application\Paginator;

use App\General\Domain\Exception\PageNotExistException;

/**
 * Class Pagination
 *
 * @package App\General\Application\Paginator
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class Pagination
{
    public const int PAGE_SIZE = 10;

    public function __construct(
        private readonly array $items,
        private readonly int $totalCount,
        private readonly ?int $offset,
        private readonly ?int $limit,
    ) {
        $this->ensureIsValidCurrentPage();
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalPageCount(): int
    {
        if ($this->limit === null) {
            return $this->totalCount === 0 ? 0 : 1;
        }

        return (int) ceil($this->totalCount / $this->limit);
    }

    public function getCurrentPage(): int
    {
        return $this->limit === null ? 1 : ((int) floor((int) $this->offset / $this->limit)) + 1;
    }

    public function getNextPage(): ?int
    {
        $next = $this->getCurrentPage() + 1;

        return $next > $this->getTotalPageCount() ? null : $next;
    }

    public function getPreviousPage(): ?int
    {
        $prev = $this->getCurrentPage() - 1;

        return $prev <= 0 ? null : $prev;
    }

    private function ensureIsValidCurrentPage(): void
    {
        if ($this->getTotalPageCount() === 0 && $this->getCurrentPage() === 1) {
            return;
        }
        if ($this->getCurrentPage() > $this->getTotalPageCount() || $this->getCurrentPage() < 1) {
            throw new PageNotExistException($this->getCurrentPage());
        }
    }
}
