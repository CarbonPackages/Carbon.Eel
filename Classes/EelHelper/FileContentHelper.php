<?php

namespace Carbon\Eel\EelHelper;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Exception;
use function file_get_contents;
use function sha1_file;
use function strncmp;
use function substr;

/**
 * @Flow\Proxy(false)
 */
class FileContentHelper implements ProtectedContextAwareInterface
{
    /**
     * Add 'resource://' to a string, if needed
     *
     * @param string $path The path
     * @return string
     */
    private function _generalizeResource(string $path): string
    {
        $resource = 'resource://';
        if (strncmp($path, $resource, 11) !== 0) {
            $path = $resource . $path;
        }
        return $path;
    }

    /**
     * Hashes a string
     *
     * @param string $hash The string to hash
     * @param int $length The length of the hash
     * @return string
     */
    private function _returnHash(string $hash, int $length)
    {
        try {
            return substr($hash, 0, $length);
        } catch (Exception $e) {
        }

        return false;
    }

    /**
     * Get the content of the file
     *
     * @param string $file The file
     * @return string|boolean
     */
    private function _returnContent(string $file)
    {
        if (file_exists($file)) {
            return file_get_contents($file);
        }

        return false;
    }

    /**
     * Returns the file content of a path. Fails silent
     *
     * @param string $path The path to the file
     * @return string|boolean
     */
    public function path(string $path)
    {
        return $this->_returnContent($this->_generalizeResource($path));
    }

    /**
     * Returns a shorten sha1 value of a file path. Fails silent
     *
     * @param string $path The path to the file
     * @param int $length The length of the hash, defaults to 8
     * @return string|boolean
     */
    public function pathHash(string $path, int $length = 8)
    {
        $file = $this->_generalizeResource($path);
        if (!file_exists($file)) {
            return false;
        }
        return $this->_returnHash(sha1_file($file), $length);
    }

    /**
     * Returns the file content of a resource. Fails silent
     *
     * @param $resource The resource
     * @return string|boolean
     */
    public function resource($resource)
    {
        return $this->_returnContent(
            'resource://' . $resource->getResource()->getSha1()
        );
    }

    /**
     * Returns a shorten sha1 value of a file property. Fails silent
     *
     * @param $resource The resource
     * @param int $length The length of the hash, defaults to 8
     * @return string | boolean
     */
    public function resourceHash($resource, int $length = 8): string
    {
        $file = $resource->getResource()->getSha1();
        if (!file_exists($file)) {
            return false;
        }
        return $this->_returnHash($file, $length);
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName The name of the method
     * @return bool
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
