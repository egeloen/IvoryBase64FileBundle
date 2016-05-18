<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Serializer\Handler;

use Ivory\Base64FileBundle\Model\Base64File;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\XmlSerializationVisitor;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Base64FileHandler implements SubscribingHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $subscribingMethods = array();

        $types = array(
            'Ivory\Base64FileBundle\Model\Base64File',
            'Symfony\Component\HttpFoundation\File\File',
        );

        foreach ($types as $type) {
            foreach (['json', 'xml', 'yml'] as $format) {
                $subscribingMethods[] = array(
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'format'    => $format,
                    'type'      => $type,
                    'method'    => $format !== 'xml' ? 'serialize' : 'serializeToXml',
                );
            }
        }

        return $subscribingMethods;
    }

    /**
     * @param VisitorInterface $visitor
     * @param File             $file
     * @param mixed[]          $type
     * @param Context          $context
     *
     * @return mixed
     */
    public function serialize(VisitorInterface $visitor, File $file, array $type, Context $context)
    {
        $file = $this->convertFileToBase64($file);

        return $visitor->visitArray(array(
            'type'  => $file->getMimeType(),
            'value' => $file->getData(true, false),
        ), $type, $context);
    }

    /**
     * @param XmlSerializationVisitor $visitor
     * @param File                    $file
     *
     * @return \DOMElement
     */
    public function serializeToXml(XmlSerializationVisitor $visitor, File $file)
    {
        $file = $this->convertFileToBase64($file);

        if ($visitor->document === null) {
            $visitor->document = $visitor->createDocument(null, null, false);
            $visitor->document->appendChild($fileNode = $visitor->document->createElement('file'));
            $visitor->setCurrentNode($fileNode);
        } else {
            $fileNode = $visitor->getCurrentNode();
        }

        $fileNode->appendChild($visitor->document->createElement('type', $file->getMimeType()));
        $fileNode->appendChild($visitor->document->createElement('value', $file->getData(true, false)));
    }

    /**
     * @param File $file
     *
     * @return Base64File
     */
    private function convertFileToBase64(File $file)
    {
        return $file instanceof Base64File ? $file : new Base64File(file_get_contents($file->getRealPath()), false);
    }
}
