<?php

/*
 * This file is part of the Lug package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BlobType;
use Ivory\Base64FileBundle\Model\Base64File;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Base64FileType extends BlobType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return parent::convertToDatabaseValue($value !== null ? $value->getData(false) : null, $platform);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value !== null) {
            return new Base64File(parent::convertToPHPValue($value, $platform), false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'base64_file';
    }
}
