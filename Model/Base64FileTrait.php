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
trait Base64FileTrait
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var resource
     */
    private $resource;

    /**
     * @param string|resource $value
     * @param bool            $encoded
     *
     * @return string
     */
    private function load($value, $encoded = true)
    {
        $this->path = tempnam(sys_get_temp_dir(), 'ivory_base64');
        $this->resource = fopen($this->path, 'w+');

        if ($encoded) {
            $filter = stream_filter_append($this->resource, 'convert.base64-decode', STREAM_FILTER_WRITE);
        }

        try {
            if (is_string($value)) {
                $this->copyStringToStream($value, $this->resource);
            } elseif (is_resource($value)) {
                $this->copyStreamToStream($value, $this->resource);
            } else {
                throw new \InvalidArgumentException(sprintf(
                    'The base64 file value must be a string or a resource, got "%s".',
                    gettype($value)
                ));
            }
        } catch (\Exception $e) {
            fclose($this->resource);

            throw $e;
        }

        if (isset($filter)) {
            stream_filter_remove($filter);
        }

        fflush($this->resource);

        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function __destruct()
    {
        if (is_resource($this->resource)) {
            fclose($this->resource);
        }

        if (file_exists($this->path)) {
            unlink($this->path);
        }
    }

    /**
     * @param bool $encoded
     * @param bool $asResource
     *
     * @return resource|string
     */
    public function getData($encoded = true, $asResource = true)
    {
        $resource = fopen('php://temp', 'rb+');

        if ($encoded) {
            $filter = stream_filter_append($resource, 'convert.base64-encode', STREAM_FILTER_WRITE);
        }

        $this->copyStreamToStream($this->resource, $resource);

        if (isset($filter)) {
            stream_filter_remove($filter);
        }

        if ($asResource) {
            return $resource;
        }

        $content = stream_get_contents($resource);
        fclose($resource);

        return $content;
    }

    /**
     * @param string   $from
     * @param resource $to
     */
    private function copyStringToStream($from, $to)
    {
        $toPosition = ftell($to);
        $success = @fwrite($to, $from);
        fseek($to, $toPosition);

        if (!$success) {
            $error = error_get_last();

            throw new \RuntimeException(sprintf(
                'An error occurred while copying the value (%s).',
                $error['message']
            ));
        }
    }

    /**
     * @param resource $from
     * @param resource $to
     */
    private function copyStreamToStream($from, $to)
    {
        $metadata = stream_get_meta_data($from);
        $seekable = $metadata['seekable'];

        $toPosition = ftell($to);

        if ($seekable) {
            $fromPosition = ftell($from);
            rewind($from);
        }

        $success = @stream_copy_to_stream($from, $to);

        if (isset($fromPosition)) {
            fseek($from, $fromPosition);
        }

        fseek($to, $toPosition);

        if (!$success) {
            $error = error_get_last();

            throw new \RuntimeException(sprintf(
                'An error occurred while copying the value (%s).',
                $error['message']
            ));
        }
    }
}
