<?php


namespace SZG\KunstmaanFeedBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class FeedCompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->createCustomFeeds($container);
        $this->supplyChain($container);
    }

    /**
     * Config:
     *
     * szg_feed:
     *      custom:
     *          rate_count: 'asc'
     *
     * @param ContainerBuilder $container
     */
    private function createCustomFeeds(ContainerBuilder $container)
    {
        $customFeeds = $container->getParameter('szg_feed.custom');
        $customFeedClass = $container->getParameter('szg_feed.feed.custom_sort_field_class');
        foreach ($customFeeds as $feed => $order) {
            $id = 'szg_feed.feed.custom_' . $feed;
            if ($container->has($id)) {
                continue;
            }
            $container->setDefinition($id, (new Definition())
                ->setClass($customFeedClass)
                ->setArguments([$feed, $order])
                ->addTag('szg_feed.feed', ['alias' => $feed])
            );
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function supplyChain(ContainerBuilder $container)
    {
        if (!$container->has('szg_feed.feed_chain')) {
            return;
        }

        $definition = $container->findDefinition('szg_feed.feed_chain');
        $taggedServices = $container->findTaggedServiceIds('szg_feed.feed');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addFeed', [new Reference($id), $attributes["alias"]]);
            }
        }
    }

}
