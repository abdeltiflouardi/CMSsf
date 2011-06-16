<?php

namespace App\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\ConfigurationInterface,
    Symfony\Component\Config\Definition\Builder\NodeBuilder,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app_core');
        
        // Adsense configuation
        $this->addAdsenseSection($rootNode);
        
        return $treeBuilder;
    }
    
    private function addAdsenseSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('adsense')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('client_id')->defaultFalse()->end()
                        ->scalarNode('height')->defaultFalse()->end()
                        ->scalarNode('width')->defaultFalse()->end()
                        ->scalarNode('colors')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;    
    }    
}
