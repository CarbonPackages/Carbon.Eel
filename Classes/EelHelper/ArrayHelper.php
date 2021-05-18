<?php

namespace Carbon\Eel\EelHelper;

/*
 *  (c) 2017 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Utility\Arrays;
use Carbon\Eel\Service\BEMService;

/**
 * @Flow\Proxy(false)
 */
class ArrayHelper implements ProtectedContextAwareInterface
{

    /**
     * Generates a BEM array
     *
     * @param string       $block     defaults to null
     * @param string       $element   defaults to null
     * @param string|array $modifiers defaults to []
     * 
     * @return array
     */
    public function BEM($block = null, $element = null, $modifiers = []): array
    {
        return BEMService::getClassNamesArray($block, $element, $modifiers);
    }

    /**
     * Adds a key / value pair to an array
     *
     * @param array  $array The array
     * @param string $key   The target key
     * @param mixed  $value The value
     * 
     * @return array
     */
    public function setKeyValue(array $array, string $key, $value): array
    {
        $array[$key] = $value;
        return $array;
    }

    /**
     * The method counts elements of a given array or countable object
     *
     * @param $countableObject
     * @return int
     */
    public function length($countableObject): int
    {
        if ($countableObject instanceof \Countable) {
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
     * @param array $array
     * @param string $key
     * @return bool
     */
    public function hasKey(array $array, string $key): bool
    {
        return isset($array[$key]);
    }

    /**
     * Returns a boolean if the array has a specific value
     * 
     * @param array $array
     * @param string $key
     * @return bool
     */
    public function hasValue(array $array, string $key): bool
    {
        return in_array($key, $array);
    }

    /**
     * Returns an array containing all the values of the first array that are present in all the arguments.
     * 
     * @param array $a Array of elements to test
     * @param array $b Array of elements to test
     * @return array the elements that are present in both arrays
     */
    public function intersect(array $a, array $b): array
    {
        return \call_user_func_array('array_intersect', \func_get_args());
    }

    /**
     * Returns the value of a nested array by following the specifed path.
     *
     * @param array &$array The array to traverse as a reference
     * @param array|string $path The path to follow. Either a simple array of keys or a string in the format 'foo.bar.baz'
     * @return mixed The value found, NULL if the path didn't exist (note there is no way to distinguish between a found NULL value and "path not found")
     * @throws \InvalidArgumentException
     */
    public function getValueByPath(array $array, $path)
    {
        return Arrays::getValueByPath($array, $path);
    }

    /**
     * Sets the given value in a nested array or object by following the specified path.
     *
     * @param array|\ArrayAccess $subject The array or ArrayAccess instance to work on
     * @param array|string $path The path to follow. Either a simple array of keys or a string in the format 'foo.bar.baz'
     * @param mixed $value The value to set
     * @return array|\ArrayAccess The modified array or object
     * @throws \InvalidArgumentException
     */
    public function setValueByPath($subject, $path, $value)
    {
        return Arrays::setValueByPath($subject, $path, $value);
    }

    /**
     * Sort an array by key
     *
     * @param array $array The array to sort
     * 
     * @return array
     */
    public function ksort(array $array): array
    {
        \ksort($array, SORT_NATURAL | SORT_FLAG_CASE);
        return $array;
    }

    /**
     * PHPs array_filter
     *
     * @param array $array The array to filter
     * 
     * @return array
     */
    public function filter(array $array): array
    {
        return array_filter($array);
    }

    /**
     * Return all the values of an array
     *
     * @param array $array The array
     * 
     * @return array Returns an indexed array of values
     */
    public function values(array $array): array
    {
        return array_values($array);
    }

    /**
     * Join the given array recursively
     * using the given separator string.
     *
     * @param array  $array     The array
     * @param string $separator The speparator, defaults to ','
     * 
     * @return string The joined string 
     */
    public function join(array $array, string $separator = ','): string
    {
        $result = '';

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
     * @param array $array        The array
     * @param bool  $preserveKeys Should the key be preserved, defaults to `false`
     * 
     * @return array
     */
    public function extractSubElements(
        array $array,
        bool $preserveKeys = false
    ): array {
        $resultArray = [];

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
        if ($variable instanceof \Traversable && iterator_count($variable)) {
            return $variable;
        }
        if (is_array($variable) && count($variable)) {
            return $variable;
        }
        return null;
    }

    /**
     * Removes duplicate values from an array
     *
     * @param array $array  The array
     * @param bool  $filter Filter the array defaults to `false`
     * 
     * @return array
     */
    public function unique(array $array, bool $filter = false): array
    {
        if ($filter) {
            $array = array_filter($array);
        }
        return array_unique($array);
    }

    /**
     * All methods are considered safe
     * 
     * @param string $methodName The name of the method
     * 
     * @return bool
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
