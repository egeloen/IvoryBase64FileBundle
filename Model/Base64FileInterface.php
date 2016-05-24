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

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
interface Base64FileInterface
{
    /**
     * @param bool $encoded
     * @param bool $asResource
     *
     * @return resource|string
     */
    public function getData($encoded = true, $asResource = true);

    /**
     * @return int
     */
    public function getSize();

    /**
     * @return string|null
     */
    public function getMimeType();
}
