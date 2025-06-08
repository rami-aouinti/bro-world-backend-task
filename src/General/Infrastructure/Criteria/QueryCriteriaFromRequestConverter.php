<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Criteria;

use App\General\Application\Criteria\QueryCriteriaDTO;
use App\General\Application\Criteria\QueryCriteriaFilterDTO;
use App\General\Application\Paginator\Pagination;
use App\General\Domain\Criteria\OperatorEnum;

use function count;

/**
 * Class QueryCriteriaFromRequestConverter
 *
 * @package App\General\Infrastructure\Criteria
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
