<?php

/*
 * This file is part of the AsseticAngularJsBundle package.
 *
 * (c) Pascal Kuendig <padakuro@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asoc\AsseticAngularJsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AsocAsseticAngularJsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($config['formatter_id'] === null) {
            $container->setAlias(
                'asoc_assetic_angular_js.template_name_formatter',
                'asoc_assetic_angular_js.template_name_formatter.default'
            );
        } else {
            $container->setAlias('asoc_assetic_angular_js.template_name_formatter', $config['formatter_id']);
        }

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('assetic.xml');
    }
}
