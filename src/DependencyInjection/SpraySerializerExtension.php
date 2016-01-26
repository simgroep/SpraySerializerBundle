<?php

namespace Spray\SerializerBundle\DependencyInjection;

use Symfony\Component\Config\Loader\LoaderInterface;
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

        if ('prod' !== $container->getParameter('kernel.environment')) {
            $loader->load('dev.xml');
        }

        $this->loadEncryptionSerializer($loader, $config, $container);
    }

    /**
     * setEncryptionSerializer
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function loadEncryptionSerializer(LoaderInterface $loader, $config, ContainerBuilder $container)
    {
        if (false === isset($config['encryption'])) {
            return;
        }

        $loader->load('encryption.xml');

        $container->setParameter('spray_serializer.encryption_key', $config['encryption']['encryption_key']);
        $encryptor = $container->getDefinition('spray_serializer.encryption_blockcipher');

        if (isset($config['encryption']['key_iteration'])) {
            $encryptor->addMethodCall('setKeyIteration', array($config['encryption']['key_iteration']));
        }
    }
}
