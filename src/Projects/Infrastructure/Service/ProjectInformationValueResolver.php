<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Service;

use App\Projects\Infrastructure\Service\DTO\ProjectInformationDTO;
use App\Shared\Infrastructure\Service\ValueResolver;

/**
 * Class ProjectInformationValueResolver
 *
 * @package App\Projects\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectInformationValueResolver
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
