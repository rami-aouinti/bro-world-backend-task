<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Criteria;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class RequestCriteriaValueResolver
 *
 * @package App\General\Infrastructure\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class RequestCriteriaValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || !is_a($argumentType, RequestCriteriaDTO::class, true)) {
            return [];
        }

        $filters = $request->query->all('filter');
        $orders = $request->query->all('order');
        $page = $request->query->get('page');

        yield new RequestCriteriaDTO($filters, $orders, $page !== null ? (int) $page : null);
    }
}
