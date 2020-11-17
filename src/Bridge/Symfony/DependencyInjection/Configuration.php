<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('populate');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('settings')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('src')->isRequired()->end()
                            ->scalarNode('dest')->isRequired()->end()
                            ->arrayNode('properties')
                                ->scalarPrototype()->defaultValue([])->end()
                            ->end()
                            ->arrayNode('ignore_properties')
                                ->scalarPrototype()->defaultValue([])->end()
                            ->end()
                            ->arrayNode('mapping')
                                ->scalarPrototype()->defaultValue([])->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}