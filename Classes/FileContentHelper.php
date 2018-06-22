<?php

namespace Carbon\Eel;

use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;

/**
 * @Flow\Proxy(false)
 */
class FileContentHelper implements ProtectedContextAwareInterface
{
    private function generalizeResource(string $path) {
        $resource = 'resource://';
        if (strncmp($path, $resource, 11) !== 0) {
            $path = $resource . $path;
        }
        return $path;
    }

    private function returnHash(string $hash, int $length) {
        try {
            return substr($hash, 0, $length);
        } catch (\Exception $e) {
        }

        return false;
    }

    private function returnContent($file) {
        try {
            return file_get_contents($file);
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Returns the file content of a path. Fails silent
     *
     * @return string | boolean
     */

    public function path(string $path)
    {
        return $this->returnContent($this->generalizeResource($path));
    }

    /**
     * Returns a shorten sha1 value of a file path. Fails silent
     *
     * @return string | boolean
     */

    public function pathHash(string $path, int $length = 8)
    {
        return $this->returnHash(sha1_file($this->generalizeResource($path)), $length);
    }

    /**
     * Returns the file content of a resource. Fails silent
     *
     * @param $resource
     * @return string | boolean
     */

    public function resource($resource)
    {
        return $this->returnContent('resource://' . $resource->getResource()->getSha1());
    }

    /**
     * Returns a shorten sha1 value of a file property. Fails silent
     *
     * @return string | boolean
     */

    public function resourceHash($resource, int $length = 8)
    {
        return $this->returnHash($resource->getResource()->getSha1(), $length);
    }

    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
