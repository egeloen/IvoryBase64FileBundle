<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Tests\Model;

use Ivory\Base64FileBundle\Model\UploadedBase64File;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class UploadedBase64FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var resource
     */
    private $base64;

    /**
     * @var resource
     */
    private $binary;

    /**
     * @var string
     */
    private $name;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->base64 = fopen(__DIR__.'/../Fixtures/Model/base64', 'rb');
        $this->binary = fopen(__DIR__.'/../Fixtures/Model/binary', 'rb');
        $this->name = 'filename.png';
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if (is_resource($this->base64)) {
            fclose($this->base64);
        }

        if (is_resource($this->binary)) {
            fclose($this->binary);
        }
    }

    public function testInheritance()
    {
        $file = new UploadedBase64File($this->base64, $this->name);

        $this->assertInstanceOf('Ivory\Base64FileBundle\Model\Base64FileInterface', $file);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\File\UploadedFile', $file);
    }

    public function testValidity()
    {
        $file = new UploadedBase64File($this->base64, $this->name);

        $this->assertTrue($file->isValid());
    }

    public function testFromBase64ResourceToBase64Resource()
    {
        $file = new UploadedBase64File($this->base64, $this->name);

        $this->assertSame($this->getBase64String(), $this->getStreamContent($file->getData()));
    }

    public function testFromBase64ResourceToBase64String()
    {
        $file = new UploadedBase64File($this->base64, $this->name);

        $this->assertSame($this->getBase64String(), $file->getData(true, false));
    }

    public function testFromBase64ResourceToBinaryResource()
    {
        $file = new UploadedBase64File($this->base64, $this->name);

        $this->assertSame($this->getBinaryString(), $this->getStreamContent($file->getData(false)));
    }

    public function testFromBase64ResourceToBinaryString()
    {
        $file = new UploadedBase64File($this->base64, $this->name);

        $this->assertSame($this->getBinaryString(), $file->getData(false, false));
    }

    public function testFromBase64StringToBase64Resource()
    {
        $file = new UploadedBase64File($original = $this->getBase64String(), $this->name);

        $this->assertSame($original, $this->getStreamContent($file->getData()));
    }

    public function testFromBase64StringToBase64String()
    {
        $file = new UploadedBase64File($original = $this->getBase64String(), $this->name);

        $this->assertSame($original, $file->getData(true, false));
    }

    public function testFromBase64StringToBinaryResource()
    {
        $file = new UploadedBase64File($this->getBase64String(), $this->name);

        $this->assertSame($this->getBinaryString(), $this->getStreamContent($file->getData(false)));
    }

    public function testFromBase64StringToBinaryString()
    {
        $file = new UploadedBase64File($this->getBase64String(), $this->name);

        $this->assertSame($this->getBinaryString(), $file->getData(false, false));
    }

    public function testFromBinaryResourceToBase64Resource()
    {
        $file = new UploadedBase64File($this->binary, $this->name, null, null, null, false);

        $this->assertSame($this->getBase64String(), $this->getStreamContent($file->getData()));
    }

    public function testFromBinaryResourceToBase64String()
    {
        $file = new UploadedBase64File($this->binary, $this->name, null, null, null, false);

        $this->assertSame($this->getBase64String(), $file->getData(true, false));
    }

    public function testFromBinaryResourceToBinaryResource()
    {
        $file = new UploadedBase64File($this->binary, $this->name, null, null, null, false);

        $this->assertSame($this->getBinaryString(), $this->getStreamContent($file->getData(false)));
    }

    public function testFromBinaryResourceToBinaryString()
    {
        $file = new UploadedBase64File($this->binary, $this->name, null, null, null, false);

        $this->assertSame($this->getBinaryString(), $file->getData(false, false));
    }

    public function testFromBinaryStringToBase64Resource()
    {
        $file = new UploadedBase64File($this->getBinaryString(), $this->name, null, null, null, false);

        $this->assertSame($this->getBase64String(), $this->getStreamContent($file->getData()));
    }

    public function testFromBinaryStringToBase64String()
    {
        $file = new UploadedBase64File($this->getBinaryString(), $this->name, null, null, null, false);

        $this->assertSame($this->getBase64String(), $file->getData(true, false));
    }

    public function testFromBinaryStringToBinaryResource()
    {
        $file = new UploadedBase64File($original = $this->getBinaryString(), $this->name, null, null, null, false);

        $this->assertSame($original, $this->getStreamContent($file->getData(false)));
    }

    public function testFromBinaryStringToBinaryString()
    {
        $file = new UploadedBase64File($original = $this->getBinaryString(), $this->name, null, null, null, false);

        $this->assertSame($original, $file->getData(false, false));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The base64 file value must be a string or a resource, got "boolean".
     */
    public function testInvalidTypeValue()
    {
        new UploadedBase64File(true, $this->name);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred while copying the value (stream_copy_to_stream(): stream filter (convert.base64-decode): invalid byte sequence).
     */
    public function testInvalidBase64ResourceValue()
    {
        new UploadedBase64File($this->binary, $this->name);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred while copying the value (fwrite(): stream filter (convert.base64-decode): invalid byte sequence).
     */
    public function testInvalidBase64StringValue()
    {
        new UploadedBase64File(stream_get_contents($this->binary), $this->name);
    }

    /**
     * @return string
     */
    private function getBase64String()
    {
        return $this->getStreamContent($this->base64);
    }

    /**
     * @return string
     */
    private function getBinaryString()
    {
        return $this->getStreamContent($this->binary);
    }

    /**
     * @param resource $resource
     *
     * @return string
     */
    private function getStreamContent($resource)
    {
        if (!is_resource($resource)) {
            $this->fail();
        }

        return stream_get_contents($resource);
    }
}
