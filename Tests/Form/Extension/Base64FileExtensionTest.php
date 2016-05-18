<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Tests\Form\Extension;

use Ivory\Base64FileBundle\Form\Extension\Base64FileExtension;
use Ivory\Base64FileBundle\Model\Base64File;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Validation;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Base64FileExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @var string
     */
    private $formType;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = Forms::createFormFactoryBuilder()
            ->addTypeExtension(new Base64FileExtension())
            ->addExtension(new ValidatorExtension(Validation::createValidator()))
            ->getFormFactory();

        $this->formType = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
            ? 'Symfony\Component\Form\Extension\Core\Type\FileType'
            : 'file';
    }

    public function testSubmitFile()
    {
        $form = $this->factory->create($this->formType);
        $form->submit($file = new File(__DIR__.'/../../Fixtures/Model/binary'));

        $this->assertTrue($form->isValid());
        $this->assertSame($file, $form->getData());
    }

    public function testSubmitNull()
    {
        $form = $this->factory->create($this->formType, null, array('base64' => true));
        $form->submit(null);

        $this->assertTrue($form->isValid());
        $this->assertNull($form->getData());
    }

    public function testSubmitValidBase64()
    {
        $form = $this->factory->create($this->formType, null, array('base64' => true));
        $form->submit($base64 = $this->getBase64Data());

        $this->assertTrue($form->isValid());
        $this->assertInstanceOf('Ivory\Base64FileBundle\Model\Base64File', $data = $form->getData());
        $this->assertSame($base64, $data->getData(true, false));
    }

    public function testSubmitInvalidBase64()
    {
        $form = $this->factory->create($this->formType, null, array('base64' => true));
        $form->submit($this->getBinaryData());

        $this->assertFalse($form->isValid());
        $this->assertNull($form->getData());
    }

    public function testValidInitialData()
    {
        $form = $this->factory->create(
            $this->formType,
            $data = new Base64File($this->getBase64Data()),
            array('base64' => true)
        );

        $this->assertSame($data, $form->getData());
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage Expected an "Ivory\Base64Bundle\Model\Base64File", got "stdClass".
     */
    public function testInvalidInitialData()
    {
        $this->factory->create($this->formType, new \stdClass(), array('base64' => true));
    }

    /**
     * @return string
     */
    private function getBase64Data()
    {
        return $this->getFileData(__DIR__.'/../../Fixtures/Model/base64');
    }

    /**
     * @return string
     */
    private function getBinaryData()
    {
        return $this->getFileData(__DIR__.'/../../Fixtures/Model/binary');
    }

    /**
     * @param string $file
     *
     * @return string
     */
    private function getFileData($file)
    {
        return file_get_contents($file);
    }
}
