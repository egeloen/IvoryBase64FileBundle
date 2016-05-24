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

        foreach (['form'] as $resource) {
            $loader->load($resource.'.xml');
        }

        $container
            ->getDefinition($formExtension = 'ivory.base64_file.form.extension')
            ->addArgument($config['default']);

        if (Kernel::VERSION_ID < 20800) {
            $container
                ->getDefinition($formExtension)
                ->clearTag('form.type_extension')
                ->addTag('form.type_extension', ['alias' => 'file']);
        }
    }
}
