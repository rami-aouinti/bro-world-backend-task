<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use App\General\Infrastructure\Service\DTO\ExceptionDTO;
use App\General\Domain\Exception\DomainException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 *
 * @package App\General\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ExceptionListener
{
    public function __construct(
        private ExceptionToHttpCodeMapperInterface $codeMapper,
        private ExceptionResponseBuilderInterface $responseBuilder,
        private string $environment = 'prod'
    ) {
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $this->getParentDomainExceptionIfExists($event->getThrowable());

        $code = $this->codeMapper->getHttpCode($exception);

        $event->setResponse(
            $this->responseBuilder->build(
                new ExceptionDTO(
                    $exception->getMessage(),
                    $code,
                    $exception->getFile(),
                    $exception->getLine(),
                    $exception->getTrace()
                ),
                $this->environment !== 'prod'
            )
        );
    }

    private function getParentDomainExceptionIfExists(\Throwable $exception): \Throwable
    {
        $result = $exception;
        while ($result !== null) {
            if ($result instanceof DomainException) {
                return $result;
            }
            $result = $result->getPrevious();
        }

        return $exception;
    }
}
