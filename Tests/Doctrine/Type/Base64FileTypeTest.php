<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Tests\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Ivory\Base64FileBundle\Doctrine\Type\Base64FileType;
use Ivory\Base64FileBundle\Model\Base64File;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Base64FileTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Base64FileType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        Type::addType('base64_file', 'Ivory\Base64FileBundle\Doctrine\Type\Base64FileType');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->type = $type = Type::getType('base64_file');
    }

    public function testInheritance()
    {
        $this->assertInstanceOf('Doctrine\DBAL\Types\BlobType', $this->type);
    }

    public function testName()
    {
        $this->assertSame('base64_file', $this->type->getName());
    }

    public function convertToDatabaseValueWithBase64FileAsString()
    {
        $result = $this->type->convertToDatabaseValue($this->createBase64File(), $this->createPlatformMock());

        $this->assertInternalType('resource', $result);
        $this->assertSame($this->getBinaryData(), stream_get_contents($result));
    }

    public function convertToDatabaseValueWithNull()
    {
        $this->assertNull($this->type->convertToDatabaseValue($this->createBase64File(), $this->createPlatformMock()));
    }

    public function testConvertToPHPValueWithString()
    {
        $result = $this->type->convertToPHPValue($this->getBinaryData(), $this->createPlatformMock());

        $this->assertInstanceOf('Ivory\Base64FileBundle\Model\Base64File', $result);
        $this->assertSame($result->getData(true, false), $this->getBase64Data());
    }

    public function testConvertToPHPValueWithResource()
    {
        $result = $this->type->convertToPHPValue($this->getBinaryResource(), $this->createPlatformMock());

        $this->assertInstanceOf('Ivory\Base64FileBundle\Model\Base64File', $result);
        $this->assertSame($result->getData(true, false), $this->getBase64Data());
    }

    public function testConvertToPHPValueWithNull()
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->createPlatformMock()));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractPlatform
     */
    private function createPlatformMock()
    {
        return $this->getMock('Doctrine\DBAL\Platforms\AbstractPlatform');
    }

    /**
     * @return Base64File
     */
    private function createBase64File()
    {
        return new Base64File($this->getBase64Data());
    }

    /**
     * @return string
     */
    private function getBase64Data()
    {
        return file_get_contents(__DIR__.'/../../Fixtures/Model/base64');
    }

    /**
     * @return string
     */
    private function getBinaryData()
    {
        return file_get_contents($this->getBinaryPath());
    }

    /**
     * @return resource
     */
    private function getBinaryResource()
    {
        return fopen($this->getBinaryPath(), 'rb');
    }

    /**
     * @return string
     */
    private function getBinaryPath()
    {
        return __DIR__.'/../../Fixtures/Model/binary';
    }
}
