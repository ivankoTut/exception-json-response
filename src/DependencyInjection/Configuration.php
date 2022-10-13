<?php

namespace IvankoTut\ExceptionJsonResponse\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('exception_json_response');

        $treeBuilder->getRootNode()
            ->fixXmlConfig('exception_json_response' , 'exclude_exceptions')
            ->children()
                ->booleanNode('enable_only_application_json')
                    ->defaultFalse()
                ->end()
                ->booleanNode('debug_mode')
                    ->defaultFalse()
                ->end()
                ->booleanNode('listen_all_exception')
                    ->defaultTrue()
                ->end()
                ->arrayNode('replace_messages')
                    ->defaultValue([])
                    ->prototype('array')
                        ->children()
                            ->scalarNode('errorClass')->isRequired()->end()
                            ->scalarNode('message')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('exclude_exceptions')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
