<?php

namespace Carbon\Eel\EelHelper;

/*
 *  (c) 2017 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  (c) 2021 Jon Uhlmann
 *  All rights reserved.
 */

use Carbon\Eel\Service\BEMService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Utility\Arrays;
use Countable;
use InvalidArgumentException;
use Traversable;
use function array_chunk;
use function call_user_func_array;
use function count;
use function func_get_args;
use function in_array;
use function is_array;
use function iterator_count;
use function iterator_to_array;
use function strlen;
use function substr;

/**
 * @Flow\Proxy(false)
 */
class ArrayHelper implements ProtectedContextAwareInterface
{

    /**
     * Generates a BEM array
     *
     * @param string $block defaults to null
     * @param string $element defaults to null
     * @param string|array $modifiers defaults to []
     *
     * @return array
     */
    public function BEM($block = null, $element = null, $modifiers = []): array
    {
        return BEMService::getClassNamesArray($block, $element, $modifiers);
    }

    /**
     * Split an array into chunks
     *
     * Chunks an array into arrays with length elements.
     * The last chunk may contain less than length elements.
     *
     * @param iterable $array The array to work on
     * @param integer $length The size of each chunk
     * @param bool $preserve_keys When set to true, keys will be preserved. Default is false, which will reindex the chunk numerically
     * @return array
     */
    public function chunk(iterable $array, int $length, bool $preserve_keys = false): array
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }
        return array_chunk($array, $length, $preserve_keys);
    }

    /**
     * The method counts elements of a given array or countable object
     *
     * @param $countableObject
     * @return int
     */
    public function length($countableObject): int
    {
        if ($countableObject instanceof Countable) {
            return $countableObject->count();
        }

        if (is_array($countableObject)) {
            return count($countableObject);
        }

        return 0;
    }

    /**
     * Returns a boolean if the array has a specific key
     *
     * @param iterable $array
     * @param string $key
     * @return bool
     */
    public function hasKey(iterable $array, string $key): bool
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }
        return isset($array[$key]);
    }

    /**
     * Returns a boolean if the array has a specific value
     *
     * @param iterable $array
     * @param string $key
     * @return bool
     */
    public function hasValue(iterable $array, string $key): bool
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }
        return in_array($key, $array);
    }

    /**
     * Returns an array containing all the values of the first array that are present in all the arguments.
     *
     * @param iterable $a Array of elements to test
     * @param iterable $b Array of elements to test
     * @return array the elements that are present in both arrays
     */
    public function intersect(iterable $a, iterable $b): array
    {
        if ($a instanceof Traversable) {
            $a = iterator_to_array($a);
        }
        if ($b instanceof Traversable) {
            $b = iterator_to_array($b);
        }
        return call_user_func_array('array_intersect', func_get_args());
    }

    /**
     * Returns the value of a nested array by following the specifed path.
     *
     * @param iterable $array The array to traverse as a reference
     * @param iterable|string $path The path to follow. Either a simple array of keys or a string in the format 'foo.bar.baz'
     * @return mixed The value found, NULL if the path didn't exist (note there is no way to distinguish between a found NULL value and "path not found")
     * @throws InvalidArgumentException
     */
    public function getValueByPath(iterable $array, $path)
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }
        if ($path instanceof Traversable) {
            $path = iterator_to_array($path);
        }
        return Arrays::getValueByPath($array, $path);
    }

    /**
     * Sets the given value in a nested array or object by following the specified path.
     *
     * @param array|\ArrayAccess $subject The array or ArrayAccess instance to work on
     * @param array|string $path The path to follow. Either a simple array of keys or a string in the format 'foo.bar.baz'
     * @param mixed $value The value to set
     * @return array|\ArrayAccess The modified array or object
     * @throws InvalidArgumentException
     */
    public function setValueByPath($subject, $path, $value)
    {
        return Arrays::setValueByPath($subject, $path, $value);
    }

    /**
     * Join the given array recursively
     * using the given separator string.
     *
     * @param iterable  $array Array with values to join
     * @param string $separator The speparator, defaults to ','
     * @return string The joined string
     */
    public function join(iterable $array, string $separator = ','): string
    {
        $result = '';

        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }

        foreach ($array as $item) {
            if (is_array($item)) {
                $result .= $this->join($item, $separator) . $separator;
            } else {
                $result .= $item . $separator;
            }
        }

        $result = substr($result, 0, 0 - strlen($separator));

        return $result;
    }

    /**
     * This method extracts sub elements to the parent level.
     *
     * An input array of type:
     * [
     *  element1 => [
     *    0 => 'value1'
     *  ],
     *  element2 => [
     *    0 => 'value2'
     *    1 => 'value3'
     *  ],
     *
     * will be converted to:
     * [
     *    0 => 'value1'
     *    1 => 'value2'
     *    2 => 'value3'
     * ]
     *
     * @param iterable $array The array
     * @param bool  $preserveKeys Should the key be preserved, defaults to `false`
     * @return array
     */
    public function extractSubElements(
        iterable $array,
        bool $preserveKeys = false
    ): array {
        $resultArray = [];
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }

        foreach ($array as $element) {
            if (is_array($element)) {
                foreach ($element as $subKey => $subElement) {
                    if ($preserveKeys) {
                        $resultArray[$subKey] = $subElement;
                    } else {
                        $resultArray[] = $subElement;
                    }
                }
            } else {
                $resultArray[] = $element;
            }
        }

        return $resultArray;
    }

    /**
     * Check if a variable is iterable and has items
     *
     * @param mixed $variable The iterable / array
     * @return mixed
     */
    public function check($variable)
    {
        if ($variable instanceof Traversable && iterator_count($variable)) {
            return $variable;
        }
        if (is_array($variable) && count($variable)) {
            return $variable;
        }
        return null;
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
