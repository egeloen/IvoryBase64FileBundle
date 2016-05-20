<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class IvoryBase64FileExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach ($this->resolveResources($config) as $resource) {
            $loader->load($resource.'.xml');
        }

        if ($config['form'] && Kernel::VERSION_ID < 20800) {
            $container->getDefinition('ivory.base64_file.form.extension')
                ->clearTag('form.type_extension')
                ->addTag('form.type_extension', array('alias' => 'file'));
        }
    }

    /**
     * @param mixed[] $config
     *
     * @return string[]
     */
    private function resolveResources(array $config)
    {
        $resources = array();

        if ($config['form']) {
            $resources[] = 'form';
        }

        if ($config['serializer']) {
            $resources[] = 'serializer';
        }

        return $resources;
    }
}
