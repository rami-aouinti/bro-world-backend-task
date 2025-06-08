<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use JsonException;
use RuntimeException;

use function sprintf;

/**
 * Class JsonContentDecoder
 *
 * @package App\General\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class JsonContentDecoder implements ContentDecoderInterface
{
    /**
     * @throws JsonException
     */
    public function decode(string $content): array
    {
        if ($content === '') {
            return [];
        }

        $result = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if ($result === null) {
            throw new RuntimeException(sprintf('Syntax error in json request content "%s"', $content));
        }

        return $result;
    }
}
