<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use App\General\Infrastructure\Service\DTO\ExceptionDTO;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
interface ExceptionResponseBuilderInterface
{
    public function build(ExceptionDTO $dto, bool $verbose = false): Response;
}
