<?php

namespace Carbon\Eel;

/*
 *  (c) 2017 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;

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
        if (!isset($block) || !is_string($block) || !$block) {
            return [];
        }
        $baseClass = $element ? "{$block}__{$element}" : "{$block}";
        $classes = [$baseClass];

        if (isset($modifiers)) {
            $modifiers = self::modifierArray($modifiers);
            foreach ($modifiers as $value) {
                $classes[] = "{$baseClass}--{$value}";
            }
        }

        return $classes;
    }

    /**
     * Generate the array for the modifiers
     * 
     * @param string|array $modifiers
     * 
     * @return array
     */
    private static function modifierArray($modifiers = []): array {
        if (is_string($modifiers)) {
            return [$modifiers];
        }
        $array = [];
        if (is_array($modifiers)) {
            foreach ($modifiers as $key => $value) {
                if (!$value) {
                    continue;
                }
                if (is_array($value)) {
                    $array = array_merge($array, self::modifierArray($value));
                } else if (is_string($value)) {
                    $array[] = $value;
                } else if (is_string($key)) {
                    $array[] = $key;
                }
            }
        }
        return array_unique($array);
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
