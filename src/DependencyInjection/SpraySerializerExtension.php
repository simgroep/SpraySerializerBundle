<?php

namespace Spray\SerializerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SpraySerializerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->setEncryptionSerializer($config, $container);
    }

    /**
     * setEncryptionSerializer
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setEncryptionSerializer($config, ContainerBuilder $container)
    {
        if (false === isset($config['encryption'])) {
            return;
        }

        $encryptionKey = $config['encryption']['encryption_key'];
        $encryptor     = new Definition('Spray\SerializerBundle\Service\BlockCipherEncryptor', array($encryptionKey));

        if (isset($config['encryption']['key_iteration'])) {
            $encryptor->addMethodCall('setKeyIteration', array($config['encryption']['key_iteration']));
        }

        $container->setDefinition('spray_encryptor.blockcipher', $encryptor);

        $arguments = array(
            new Reference('spray_serializer'),
            new Reference('spray_encryptor.blockcipher')
        );
        $encryptingSerializer = new Definition('Spray\SerializerBundle\Integration\BroadwayEncryptingSerializer', $arguments);
        $container->setDefinition('spray_serializer_encrypt.integration.broadway', $encryptingSerializer);

        $container->setAlias('broadway.serializer.payload', 'spray_serializer_encrypt.integration.broadway');
    }
}
