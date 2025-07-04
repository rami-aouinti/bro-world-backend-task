<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Bus;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * Class SimulateMicroserviceMiddleware
 *
 * @package App\General\Infrastructure\Bus
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class SimulateMicroserviceMiddleware implements MiddlewareInterface
{
    public function __construct(private HandlersLocatorInterface $handlersLocator)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        foreach ($this->handlersLocator->getHandlers($envelope) as $handler) {
            if (!$this->isSameDomain($envelope->getMessage(), $handler)) {
                $envelope = $envelope->with(HandledStamp::fromDescriptor($handler, null));
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }

    private function isSameDomain(object $message, HandlerDescriptor $handler): bool
    {
        $messageDomain = explode('\\', $message::class)[1] ?? null;
        $handlerDomain = explode('\\', $handler->getName())[1] ?? null;

        if ($messageDomain === null || $handlerDomain === null) {
            return false;
        }

        return $messageDomain === $handlerDomain;
    }
}
