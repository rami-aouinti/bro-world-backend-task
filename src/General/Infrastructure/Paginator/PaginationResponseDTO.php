<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Paginator;

use App\General\Application\Paginator\Pagination;
use JsonSerializable;

/**
 * Class PaginationResponseDTO
 *
 * @package App\General\Infrastructure\Paginator
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class PaginationResponseDTO implements JsonSerializable
{
    public function __construct(
        public array $items,
        public int $total,
        public int $current,
        public ?int $next,
        public ?int $previous,
    ) {
    }

    public static function createFromPagination(Pagination $pagination, callable $itemsCallback = null): self
    {
        return new self(
            $itemsCallback === null ? $pagination->getItems() : $itemsCallback($pagination->getItems()),
            $pagination->getTotalPageCount(),
            $pagination->getCurrentPage(),
            $pagination->getNextPage(),
            $pagination->getPreviousPage()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => $this->items,
            'page' => [
                'total' => $this->total,
                'current' => $this->current,
                'next' => $this->next,
                'previous' => $this->previous,
            ],
        ];
    }
}
