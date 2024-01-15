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
                $result[] = $this->arrayToString($argument, false);
                continue;
            }

            $result[] = $this->returnValue($argument, true);
        }

        return sprintf('%s(%s)', $name, implode(',', $result));
    }

    /**
     * Generate an object for AlpineJS x-data="{name: arg1, name2: arg2, ..nameN: argN}" https://alpinejs.dev/directives/data
     *
     * @param array|iterable $array
     * @return string
     */
    public function object($array): ?string
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }

        if (!is_array($array)) {
            return null;
        }

        return $this->keyedArrayToString($array, false);
    }

    /**
     * Use this to pass a javascript expression inside of the `AlpineJS.object` or `AlpineJS.xData` helper
     *
     * @param string $value
     * @return string
     */
    public function expression($value): string
    {
        return sprintf('__EXPRESSION__%s', $value);
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
        if (is_string($value) && strpos($value, '__EXPRESSION__') === 0) {
            return substr($value, 14);
        }

        if (is_null($value)) {
            if ($returnNull) {
                return 'null';
            }
            return null;
        }

        if (is_numeric($value)) {
            return $value;
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
     * Generates a string from an array with keys
     *
     * @param iterable|array $array
     * @param bool $outputNull
     * @return string
     */
    private function keyedArrayToString($array, $returnNull = false): string
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }

        $result = [];

        /**
         * @var array $array
         */
        foreach ($array as $key => $value) {
            if (is_array($value) || $value instanceof Traversable) {
                $result[] = sprintf('%s:%s', $key, $this->arrayToString($value, $returnNull));
                continue;
            }

            $value = $this->returnValue($value, $returnNull);

            if (is_null($value)) {
                if (!$returnNull) {
                    continue;
                }

                $value = 'null';
            }

            $result[] = sprintf('%s:%s', $key, $value);
        }
        return sprintf('{%s}', implode(',', $result));
    }

    /**
     * Generates a string from an array
     *
     * @param iterable|array $array
     * @param bool $outputNull
     * @return string
     */
    private function arrayToString($array, $returnNull = false): string
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }

        $result = [];

        /**
         * @var array $array
         */
        if (array_is_list($array)) {
            foreach ($array as $value) {
                if (is_array($value) || $value instanceof Traversable) {
                    $result[] = $this->arrayToString($value, $returnNull);
                    continue;
                }

                $result[] = $this->returnValue($value, true);
            }

            return sprintf('[%s]', implode(',', $result));
        }

        return $this->keyedArrayToString($array, $returnNull);
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
