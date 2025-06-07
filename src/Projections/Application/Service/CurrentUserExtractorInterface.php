<?php

declare(strict_types=1);

namespace App\Projections\Application\Service;

use App\Projections\Domain\Entity\UserProjection;

interface CurrentUserExtractorInterface
{
    public function extract(): UserProjection;
}
