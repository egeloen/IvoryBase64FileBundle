<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Tests\Serializer\Handler;

use Ivory\Base64FileBundle\Model\Base64File;
use Ivory\Base64FileBundle\Serializer\Handler\Base64FileHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Base64FileHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->serializer = SerializerBuilder::create()
            ->configureHandlers(function(HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new Base64FileHandler());
            })
            ->build();
    }

    /**
     * @dataProvider fileDataProvider
     */
    public function testSerializeToJsonAsRoot(File $file)
    {
        $this->assertSame(
            json_encode(array('type' => 'image/png', 'value' => $this->getBase64Data())),
            $this->serializer->serialize($file, 'json')
        );
    }

    /**
     * @dataProvider fileDataProvider
     */
    public function testSerializeToJsonAsEmbed(File $file)
    {
        $this->assertSame(
            json_encode(array('file' => array('type' => 'image/png', 'value' => $this->getBase64Data()))),
            $this->serializer->serialize(array('file' => $this->createBase64File()), 'json')
        );
    }

    /**
     * @dataProvider fileDataProvider
     */
    public function testSerializeToXmlAsRoot(File $file)
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<file>
  <type>image/png</type>
  <value>{$this->getBase64Data()}</value>
</file>

EOF;

        $this->assertSame($expected, $this->serializer->serialize($this->createBase64File(), 'xml'));
    }

    /**
     * @dataProvider fileDataProvider
     */
    public function testSerializeToXmlAsEmbed(File $file)
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <entry>
    <type>image/png</type>
    <value>{$this->getBase64Data()}</value>
  </entry>
</result>

EOF;

        $this->assertSame($expected, $this->serializer->serialize(array('file' => $this->createBase64File()), 'xml'));
    }

    /**
     * @dataProvider fileDataProvider
     */
    public function testSerializeToYamlAsRoot(File $file)
    {
        $this->assertSame(
            Yaml::dump(array('type' => 'image/png', 'value' => $this->getBase64Data())),
            $this->serializer->serialize($this->createBase64File(), 'yml')
        );
    }

    /**
     * @dataProvider fileDataProvider
     */
    public function testSerializeToYamlAsEmbed(File $file)
    {
        $this->assertSame(
            Yaml::dump(array('file' => array('type' => 'image/png', 'value' => $this->getBase64Data()))),
            $this->serializer->serialize(array('file' => $this->createBase64File()), 'yml')
        );
    }

    /**
     * @return File[][]
     */
    public function fileDataProvider()
    {
        return array(
            array($this->createFile()),
            array($this->createBase64File()),
        );
    }

    /**
     * @return File
     */
    private function createFile()
    {
        return new File(__DIR__.'/../../Fixtures/Model/binary');
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
}
