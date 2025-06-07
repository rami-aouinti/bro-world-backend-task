<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Service\DTO;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProjectInformationDTO
 *
 * @package App\Projects\Infrastructure\Service\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectInformationDTO
{
    public function __construct(
        #[Groups(['create', 'update'])]
        #[Assert\NotBlank]
        public string $name = '',
        #[Groups(['create', 'update'])]
        public string $description = '',
        #[Groups(['create', 'update'])]
        public string $finishDate = '',
        #[Groups(['update'])]
        #[Assert\NotBlank]
        public string $version = ''
    ) {
    }
}
