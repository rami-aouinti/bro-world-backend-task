<?php

declare(strict_types=1);

namespace App\Projections\Application\Query;

use App\General\Application\Bus\Query\QueryInterface;

/**
 * Class TaskQuery
 *
 * @package App\Projections\Application\Query
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskQuery implements QueryInterface
{
    public function __construct(public string $id)
    {
    }
}
