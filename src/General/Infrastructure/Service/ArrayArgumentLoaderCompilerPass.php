<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use function sprintf;

/**
 * Class ArrayArgumentLoaderCompilerPass
 *
 * @package App\General\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ArrayArgumentLoaderCompilerPass implements CompilerPassInterface
{
    public const string TAG = 'task_manager.argument_loader';

    public const string TARGET_TAG_FIELD = 'target_tag';

    public function process(ContainerBuilder $container): void
    {
        $parentServices = $container->findTaggedServiceIds(self::TAG);
        foreach ($parentServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            foreach ($tags as $tag) {
                if (!isset($tag[self::TARGET_TAG_FIELD])) {
                    $message = sprintf(
                        'The tag "%s" must have %s field.', self::TAG, self::TARGET_TAG_FIELD
                    );
                    throw new InvalidConfigurationException($message);
                }
                $target = $tag[self::TARGET_TAG_FIELD];
                $childServices = $container->findTaggedServiceIds($target);
                $definition->addArgument(array_keys($childServices));
            }
        }
    }
}
