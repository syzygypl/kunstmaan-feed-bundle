<?php


namespace SZG\FeedBundle\Feed\ElasticSearch\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FeedCompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('szg_feed.feed_chain')) {
            return;
        }

        $definition = $container->findDefinition(
            'szg_feed.feed_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'szg_feed.feed'
        );

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addFeed', [new Reference($id, $attributes["alias"])]);
            }
        }
    }

}
