<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Bridge\Symfony\DependencyInjection;

use Kenny1911\Populate\Settings\Settings;
use Kenny1911\Populate\Settings\SettingsInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PopulateExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setDefinition(SettingsInterface::class, new Definition(Settings::class, [$config['settings']]));

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}