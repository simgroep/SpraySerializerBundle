<?php

namespace Spray\SerializerBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TaggedSerializerListenerCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $listenerTag;

    /**
     * @var string
     */
    private $defaultSerializerService;

    /**
     * Injects listeners into desired serializer service
     *
     * @param string $listenerTag
     * @param string $defaultSerializerService
     */
    public function __construct(
        $listenerTag = 'spray_serializer_listener',
        $defaultSerializerService =  'spray_serializer_simple'
    ) {
        $this->listenerTag = $listenerTag;
        $this->defaultSerializerService = $defaultSerializerService;
    }

    public function process(ContainerBuilder $container)
    {
        $serializerService = $this->defaultSerializerService;
        foreach ($container->findTaggedServiceIds($this->listenerTag) as $id => $configs) {
            foreach ($configs as $config) {
                if (isset($config['serializer-id'])) {
                    $serializerService = $config['serializer-id'];
                }

                if (! $container->hasDefinition($serializerService)) {
                    throw new \InvalidArgumentException(sprintf(
                        'The serializer service with id "%s" does not exists',
                        $serializerService
                    ));
                }

                $serializerDefinition = $container->getDefinition($serializerService);
                $serializerDefinition->addMethodCall('attach', [
                    new Reference($id)
                ]);
            }
        }
    }
}