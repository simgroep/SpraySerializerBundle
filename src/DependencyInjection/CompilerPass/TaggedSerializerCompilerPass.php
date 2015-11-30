<?php

namespace Spray\SerializerBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TaggedSerializerCompilerPass implements CompilerPassInterface
{
    /**
     * Find services tagged with "spray_serializer" and attach them to the 
     * serializer registry.
     * 
     * @param ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('spray_serializer.serializer_registry');
        $tagged = $container->findTaggedServiceIds('spray_serializer');
        
        foreach ($tagged as $id => $tags) {
            foreach ($tags as $attr) {
                $registryDefinition->addMethodCall('add', array(
                    new Reference($id)
                ));
            }
        }
    }
}
