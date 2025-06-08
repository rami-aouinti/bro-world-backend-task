<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Shared\Infrastructure\Service\DTO\ExceptionDTO;

/**
 * Class JsonExceptionResponseBuilder
 *
 * @package App\Shared\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class JsonExceptionResponseBuilder implements ExceptionResponseBuilderInterface
{
    public function build(ExceptionDTO $dto, bool $verbose = false): Response
    {
        $data = [
            'code' => $dto->httpCode,
            'message' => $dto->message,
        ];

        if ($verbose) {
            $data['file'] = $dto->file;
            $data['line'] = $dto->line;
            $data['trace'] = $dto->trace;
        }

        return new JsonResponse($data, $dto->httpCode);
    }
}
