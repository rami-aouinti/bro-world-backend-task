<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Criteria;

use App\Shared\Application\Criteria\QueryCriteriaDTO;
use App\Shared\Application\Criteria\QueryCriteriaFilterDTO;
use App\Shared\Application\Paginator\Pagination;
use App\Shared\Domain\Criteria\OperatorEnum;

use function count;

/**
 * Class QueryCriteriaFromRequestConverter
 *
 * @package App\Shared\Infrastructure\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class QueryCriteriaFromRequestConverter implements QueryCriteriaFromRequestConverterInterface
{
    public const string DEFAULT_OPERATOR = OperatorEnum::Equal->value;

    public function convert(RequestCriteriaDTO $dto): QueryCriteriaDTO
    {
        $filters = [];
        foreach ($dto->filters as $filterMetadata => $value) {
            $parts = explode(':', (string) $filterMetadata);

            $operator = self::DEFAULT_OPERATOR;
            $property = $parts[0];
            if (count($parts) > 1) {
                $operator = mb_strtolower($parts[1]);
            }
            $filters[] = new QueryCriteriaFilterDTO($property, $operator, $value);
        }

        $orders = [];
        foreach ($dto->orders as $orderMetadata) {
            $first = $orderMetadata[0];
            $isAsc = $first === '-';
            $property = ltrim($orderMetadata, '-+');
            $orders[$property] = $isAsc;
        }

        $page = $dto->page ?? 1;

        return new QueryCriteriaDTO(
            $filters,
            $orders,
            ($page - 1) * Pagination::PAGE_SIZE,
            Pagination::PAGE_SIZE
        );
    }
}
