<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

use Symfony\Component\HttpFoundation\Response;
use App\Shared\Infrastructure\Service\DTO\ExceptionDTO;

interface ExceptionResponseBuilderInterface
{
    public function build(ExceptionDTO $dto, bool $verbose = false): Response;
}
