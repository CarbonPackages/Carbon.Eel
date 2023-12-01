<?php

namespace Carbon\Eel\EelHelper;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Traversable;

/**
 * @Flow\Proxy(false)
 */
class AlpineJSHelper implements ProtectedContextAwareInterface
{
    /**
     * Generate a function call for AlpineJS x-data="name(arg1, arg2, ..argN)" https://alpinejs.dev/globals/alpine-data
     *
     * @param string $name
     * @param iterable|mixed $arguments
     * @return string
     */
    public function xData(string $name, ...$arguments): string
    {
        $result = [];
        foreach ($arguments as $argument) {
            if (is_array($argument) || $argument instanceof Traversable) {
                $result[] = $this->arrayToString($argument);
                continue;
            }

            $result[] = $this->returnValue($argument, true);
        }

        return sprintf('%s(%s)', $name, implode(',', $result));
    }

    /**
     * Return values for the xData function
     *
     * @param mixed $value
     * @param bool $returnNull
     * @return mixed
     */
    private function returnValue($value, $returnNull = false)
    {
        if (is_null($value)) {
            if ($returnNull) {
                return 'null';
            }
            return null;
        }

        if (is_string($value)) {
            return sprintf("'%s'", $value);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }

    /**
     * Generates a string from an array
     *
     * @param iterable|mixed $arguments
     * @return string
     */
    private function arrayToString($array): string
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }

        $result = [];

        if (array_is_list($array)) {
            foreach ($array as $value) {
                if (is_array($value) || $value instanceof Traversable) {
                    $result[] = $this->arrayToString($value);
                    continue;
                }

                $result[] = $this->returnValue($value, true);
            }

            return sprintf('[%s]', implode(',', $result));
        }

        foreach ($array as $key => $value) {
            if (is_array($value) || $value instanceof Traversable) {
                $result[] = sprintf('%s:%s', $key, $this->arrayToString($value));
                continue;
            }

            $value = $this->returnValue($value, false);

            if (is_null($value)) {
                continue;
            }

            $result[] = sprintf('%s:%s', $key, $value);
        }

        return sprintf('{%s}', implode(',', $result));
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
