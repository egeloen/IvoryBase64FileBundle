<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Form\DataTransformer;

use Ivory\Base64FileBundle\Model\Base64FileInterface;
use Ivory\Base64FileBundle\Model\UploadedBase64File;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Base64FileTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($value === null) {
            return [
                'value'    => null,
                'name'     => null,
                'mimeType' => null,
                'size'     => null,
            ];
        }

        if (!$value instanceof Base64FileInterface) {
            throw new TransformationFailedException(sprintf(
                'Expected an "%s", got "%s".',
                Base64FileInterface::class,
                $this->getVariableType($value)
            ));
        }

        $uploadedFile = $value instanceof UploadedBase64File;

        return [
            'value'    => $value->getData(true, false),
            'name'     => $uploadedFile ? $value->getClientOriginalName() : null,
            'mimeType' => $uploadedFile ? $value->getClientMimeType() : $value->getMimeType(),
            'size'     => $uploadedFile ? $value->getClientSize() : $value->getSize(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return;
        }

        if (!is_array($value)) {
            throw new TransformationFailedException(sprintf(
                'Expected an array, got "%s".',
                $this->getVariableType($value)
            ));
        }

        if (!isset($value['name'])) {
            throw new TransformationFailedException('Missing base 64 file name.');
        }

        if (!isset($value['value'])) {
            throw new TransformationFailedException('Missing base 64 file value.');
        }

        try {
            return new UploadedBase64File(
                $value['value'],
                $value['name'],
                isset($value['mimeType']) ? $value['mimeType'] : null,
                isset($value['size']) ? $value['size'] : null
            );
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @param mixed $variable
     *
     * @return string
     */
    private function getVariableType($variable)
    {
        return is_object($variable) ? get_class($variable) : gettype($variable);
    }
}
