<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Tests\DependencyInjection;

use Ivory\Base64FileBundle\DependencyInjection\IvoryBase64FileExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractIvoryBase64FileExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->container = new ContainerBuilder();

        $this->container->registerExtension($extension = new IvoryBase64FileExtension());
        $this->container->loadFromExtension($extension->getAlias());
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $configuration
     */
    abstract protected function loadConfiguration(ContainerBuilder $container, $configuration);

    public function testFormDisabled()
    {
        $this->container->compile();

        $this->assertFalse($this->container->has('ivory.base_64.form.extension'));
    }

    public function testFormEnabled()
    {
        $this->loadConfiguration($this->container, 'form');
        $this->container->compile();

        $this->assertTrue($this->container->has($extension = 'ivory.base64_file.form.extension'));

        $this->assertInstanceOf(
            'Ivory\Base64FileBundle\Form\Extension\Base64FileExtension',
            $this->container->get($extension)
        );

        $tag = $this->container->getDefinition($extension)->getTag('form.type_extension');

        if (Kernel::VERSION_ID >= 30000) {
            $this->assertSame(array(array(
                'extended_type' => 'Symfony\Component\Form\Extension\Core\Type\FileType',
                'extended-type' => 'Symfony\Component\Form\Extension\Core\Type\FileType',
            )), $tag);
        } else {
            $this->assertSame(array(array('alias' => 'file')), $tag);
        }
    }

    public function testSerializerDisabled()
    {
        $this->container->compile();

        $this->assertFalse($this->container->has('ivory.base_64.serializer.handler'));
    }

    public function testSerializerEnabled()
    {
        $this->loadConfiguration($this->container, 'serializer');
        $this->container->compile();

        $this->assertTrue($this->container->has($handler = 'ivory.base_64.serializer.handler'));

        $this->assertInstanceOf(
            'Ivory\Base64FileBundle\Serializer\Handler\Base64FileHandler',
            $this->container->get($handler)
        );

        $this->assertSame(
            array(array()),
            $this->container->getDefinition($handler)->getTag('jms_serializer.subscribing_handler')
        );
    }
}
