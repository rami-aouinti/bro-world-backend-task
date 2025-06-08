<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class ValueResolver
 *
 * @package App\General\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
abstract class ValueResolver implements ValueResolverInterface
{
    public function __construct(private readonly ContentDecoderInterface $decoder)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || !is_a($argumentType, $this->supportClass(), true)) {
            return [];
        }

        $attributes = $this->decoder->decode($request->getContent());

        return $this->doResolve($attributes);
    }

    /**
     * @return class-string
     */
    abstract protected function supportClass(): string;

    abstract protected function doResolve(array $attributes): iterable;
}
