<?php

declare(strict_types=1);

namespace App\Projections\Application\Query;

use App\Shared\Application\Bus\Query\QueryInterface;

final readonly class ProjectQuery implements QueryInterface
{
    public function __construct(public string $id)
    {
    }
}
