<?php

namespace Carbon\Eel\Service;

use Traversable;
use function array_filter;
use function array_map;
use function array_merge;
use function array_reverse;
use function array_walk_recursive;
use function count;
use function explode;
use function is_array;
use function iterator_to_array;

class MergeClassesService
{
    /**
     * Merge strings and arrays to a string with unique values, separated by an empty space
     *
     * @param iterable|mixed $arguments Optional variable list of arrays / values
     * @return string|null The merged string
     */
    public static function merge(...$arguments): ?string
    {
        $mergedArray = self::mergeArray(...$arguments);
        if ($mergedArray) {
            return implode(' ', $mergedArray);
        }
        return null;
    }

    /**
     * Merge strings and arrays to a array of classes
     *
     * @param iterable|mixed $arguments Optional variable list of arrays / values
     * @return array|null The array with strings
     */
    public static function mergeArray(...$arguments): ?array
    {
        // Create an array with trimmed values
        foreach ($arguments as &$argument) {
            $argument = self::flattenMergeArgument($argument);
        }
        $mergedArray = array_merge(...$arguments);

        if (count($mergedArray)) {
            // Reverse array back and forth so array_unique remove the values from the beginning
            return array_reverse(array_unique(array_reverse($mergedArray)));
        }

        return null;
    }

    /**
     * Flatten an argument from the merge function
     *
     * @param mixed $value
     * @return array
     */
    private static function flattenMergeArgument($value): array
    {
        if ($value === true) {
            return ['true'];
        }
        if (is_scalar($value)) {
            return array_map('trim', array_filter(explode(' ', (string)$value)));
        }
        $return = [];
        if ($value instanceof Traversable) {
            $value = iterator_to_array($value);
        }
        if (is_array($value)) {
            array_walk_recursive($value, function ($a, $key) use (&$return) {
                if ($a === true) {
                    $a = [$key];
                }
                if (is_scalar($a)) {
                    $a = explode(' ', (string)$a);
                }
                if ($a instanceof Traversable) {
                    $a = iterator_to_array($a, false);
                }
                if (is_array($a)) {
                    foreach ($a as $b) {
                        $return[] = $b;
                    }
                }
            });
        }
        return array_map('trim', array_filter($return));
    }
}
