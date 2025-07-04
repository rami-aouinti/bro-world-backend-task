<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Service;

use App\General\Infrastructure\Service\ValueResolver;
use App\Projects\Infrastructure\Service\DTO\ProjectInformationDTO;

/**
 * Class ProjectInformationValueResolver
 *
 * @package App\Projects\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectInformationValueResolver extends ValueResolver
{
    protected function supportClass(): string
    {
        return ProjectInformationDTO::class;
    }

    protected function doResolve(array $attributes): iterable
    {
        $version = isset($attributes['version']) ? (string) $attributes['version'] : '';
        yield new ProjectInformationDTO(
            $attributes['name'] ?? '',
            $attributes['description'] ?? '',
            $attributes['finishDate'] ?? '',
            $version
        );
    }
}
