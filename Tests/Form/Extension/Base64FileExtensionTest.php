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
use Ivory\Base64FileBundle\Model\UploadedBase64File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->addTypeExtension(new Base64FileExtension(false))
            ->addExtension(new ValidatorExtension(Validation::createValidator()))
            ->getFormFactory();

        $this->formType = method_exists(AbstractType::class, 'getBlockPrefix') ? FileType::class : 'file';
    }

    public function testSubmitFile()
    {
        $form = $this->factory
            ->create($this->formType)
            ->submit($file = new File(__DIR__.'/../../Fixtures/Model/binary'));

        $this->assertTrue($form->isValid());
        $this->assertSame($file, $form->getData());
    }

    public function testSubmitNull()
    {
        $form = $this->factory
            ->create($this->formType, null, ['base64' => true])
            ->submit(null);

        $this->assertTrue($form->isValid());
        $this->assertNull($form->getData());
    }

    public function testSubmitMinimalValidBase64()
    {
        $form = $this->factory
            ->create($this->formType, null, ['base64' => true])
            ->submit($submitData = $this->getMinimalSubmitData());

        $this->assertTrue($form->isValid());
        $this->assertInstanceOf(UploadedBase64File::class, $data = $form->getData());

        $this->assertSame($submitData['value'], $data->getData(true, false));
        $this->assertSame($submitData['name'], $data->getClientOriginalName());
        $this->assertSame('application/octet-stream', $data->getClientMimeType());
        $this->assertNull($data->getClientSize());
    }

    public function testSubmitMaximalValidBase64()
    {
        $form = $this->factory
            ->create($this->formType, null, ['base64' => true])
            ->submit($submitData = $this->getMaximalSubmitData());

        $this->assertTrue($form->isValid());
        $this->assertInstanceOf(UploadedBase64File::class, $data = $form->getData());

        $this->assertSame($submitData['value'], $data->getData(true, false));
        $this->assertSame($submitData['name'], $data->getClientOriginalName());
        $this->assertSame($submitData['mimeType'], $data->getClientMimeType());
        $this->assertSame($submitData['size'], $data->getClientSize());
    }

    public function testSubmitInvalidStructure()
    {
        $form = $this->factory
            ->create($this->formType, null, ['base64' => true])
            ->submit('foo');

        $this->assertFalse($form->isValid());
        $this->assertNull($form->getData());
    }

    public function testSubmitMissingName()
    {
        $submitData = $this->getMinimalSubmitData();
        unset($submitData['name']);

        $form = $this->factory
            ->create($this->formType, null, ['base64' => true])
            ->submit($submitData);

        $this->assertFalse($form->isValid());
        $this->assertNull($form->getData());
    }

    public function testSubmitMissingValue()
    {
        $submitData = $this->getMinimalSubmitData();
        unset($submitData['value']);

        $form = $this->factory
            ->create($this->formType, null, ['base64' => true])
            ->submit($submitData);

        $this->assertFalse($form->isValid());
        $this->assertNull($form->getData());
    }

    public function testSubmitInvalidBase64Value()
    {
        $submitData = $this->getMinimalSubmitData();
        $submitData['value'] = $this->getBinaryData();

        $form = $this->factory
            ->create($this->formType, null, ['base64' => true])
            ->submit($submitData);

        $this->assertFalse($form->isValid());
        $this->assertNull($form->getData());
    }

    public function testValidInitialData()
    {
        $form = $this->factory->create(
            $this->formType,
            $data = new Base64File($this->getBase64Data()),
            ['base64' => true]
        );

        $this->assertSame($data, $form->getData());
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage Expected an "Ivory\Base64FileBundle\Model\Base64FileInterface", got "stdClass".
     */
    public function testInvalidInitialData()
    {
        $this->factory->create($this->formType, new \stdClass(), ['base64' => true]);
    }

    /**
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @expectedExceptionMessage The option "base64" with value "foo" is expected to be of type "bool"
     */
    public function testInvalidBase64Option()
    {
        $this->factory->create($this->formType, null, ['base64' => 'foo']);
    }

    /**
     * @return string[]
     */
    private function getMinimalSubmitData()
    {
        return [
            'value' => $this->getBase64Data(),
            'name'  => 'filename.png',
        ];
    }

    /**
     * @return mixed[]
     */
    private function getMaximalSubmitData()
    {
        return array_merge($this->getMinimalSubmitData(), [
            'size'     => strlen($this->getBinaryData()),
            'mimeType' => 'image/png',
        ]);
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
