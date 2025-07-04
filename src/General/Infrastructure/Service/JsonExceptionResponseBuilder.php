<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use App\General\Infrastructure\Service\DTO\ExceptionDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonExceptionResponseBuilder
 *
 * @package App\General\Infrastructure\Service
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
