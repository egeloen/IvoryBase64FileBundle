<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Base64File extends File implements Base64FileInterface
{
    use Base64FileTrait {
        __construct as private constructTrait;
    }

    /**
     * @param string|resource $value
     * @param bool            $encoded
     */
    public function __construct($value, $encoded = true)
    {
        $this->constructTrait($value, $encoded);
        $metadata = stream_get_meta_data($this->resource);

        parent::__construct($metadata['uri']);
    }
}
