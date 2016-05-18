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

use Ivory\Base64FileBundle\Model\Base64File;
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
            return;
        }

        if (!$value instanceof Base64File) {
            throw new TransformationFailedException(sprintf(
                'Expected an "Ivory\Base64Bundle\Model\Base64File", got "%s".',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return $value->getData(true, false);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return;
        }

        try {
            return new Base64File($value);
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage());
        }
    }
}
