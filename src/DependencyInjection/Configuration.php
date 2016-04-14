<?php

namespace SZG\KunstmaanFeedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $custom = $treeBuilder
            ->root('kunstmaan_feed')
            ->children()
            ->arrayNode('custom');

        $custom
            ->validate()
                ->ifInArray(['random', 'recent'])
                ->thenInvalid('Invalid custom feed type name "%s"');

        $custom
            ->defaultValue([])
            ->prototype('scalar');

        return $treeBuilder;
    }
}
