<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use App\General\Domain\Exception\DomainException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExceptionToHttpCodeMapper
 *
 * @package App\General\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ExceptionToHttpCodeMapper implements ExceptionToHttpCodeMapperInterface
{
    private const int CODE_DEFAULT = Response::HTTP_INTERNAL_SERVER_ERROR;

    private array $map = [
        DomainException::CODE_UNAUTHORIZED => Response::HTTP_UNAUTHORIZED,
        DomainException::CODE_FORBIDDEN => Response::HTTP_FORBIDDEN,
        DomainException::CODE_NOT_FOUND => Response::HTTP_NOT_FOUND,
        DomainException::CODE_UNPROCESSABLE_ENTITY => Response::HTTP_UNPROCESSABLE_ENTITY,
    ];

    public function getHttpCode(\Throwable $exception): int
    {
        return $this->map[$exception->getCode()] ?? self::CODE_DEFAULT;
    }
}
