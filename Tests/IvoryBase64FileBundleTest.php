<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Tests;

use Ivory\Base64FileBundle\IvoryBase64FileBundle;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class IvoryBase64FileBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IvoryBase64FileBundle
     */
    private $bundle;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->bundle = new IvoryBase64FileBundle();
    }

    public function testInheritance()
    {
        $this->assertInstanceOf('Symfony\Component\HttpKernel\Bundle\Bundle', $this->bundle);
    }
}
