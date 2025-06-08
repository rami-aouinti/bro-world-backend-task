<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Service;

use App\General\Infrastructure\Service\ValueResolver;
use App\Projects\Infrastructure\Service\DTO\TaskInformationDTO;

/**
 * Class TaskInformationValueResolver
 *
 * @package App\Projects\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskInformationValueResolver extends ValueResolver
{
    protected function supportClass(): string
    {
        return TaskInformationDTO::class;
    }

    protected function doResolve(array $attributes): iterable
    {
        $version = isset($attributes['version']) ? (string) $attributes['version'] : '';
        yield new TaskInformationDTO(
            $attributes['name'] ?? '',
            $attributes['brief'] ?? '',
            $attributes['description'] ?? '',
            $attributes['startDate'] ?? '',
            $attributes['finishDate'] ?? '',
            $version
        );
    }
}
