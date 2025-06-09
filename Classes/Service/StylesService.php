<?php

namespace Carbon\Eel\Service;

use Traversable;
use Carbon\Eel\Service\StringConversionService;
use function array_filter;
use function array_map;
use function array_merge;
use function count;
use function explode;
use function is_array;
use function iterator_to_array;

class StylesService
{
    /**
     * Generate styles from the given arguments
     *
     * @param iterable|mixed $arguments
     * @return string|null The merged string
     */
    public static function styles(...$arguments): ?string
    {
        $keyedResult = [];
        foreach ($arguments as $argument) {
            if ($argument instanceof Traversable) {
                $argument = iterator_to_array($argument);
            }
            if (is_array($argument)) {
                foreach ($argument as $key => $value) {
                    if ((is_numeric($value) || ($value && is_string($value))) && is_string($key)) {
                        $keyedResult[$key] = $value;
                    }
                }
            } elseif (is_string($argument) && trim($argument) !== '') {
                // If it's a string, convert it to an array
                $styleArray = self::styleToArray($argument);
                if (is_array($styleArray)) {
                    $keyedResult = array_merge($keyedResult, $styleArray);
                }
            }
        }

        $result = [];
        foreach ($keyedResult as $key => $value) {
            $key = StringConversionService::convertCamelCase($key, '-');
            $result[] = sprintf('%s:%s;', $key, $value);
        }

        if (count($result)) {
            return implode('', $result);
        }

        return null;
    }


    /**
     * Converts a CSS style string (e.g. "--border-color:10px;color:red;")
     * into an associative array of property => value.
     *
     * @param string $styleString Input style string, declarations separated by semicolons.
     * @return array Associative array mapping each CSS property to its value.
     */
    public static function styleToArray(mixed $styleString): array
    {
        if (!is_string($styleString) || trim($styleString) === '') {
            if (is_array($styleString)) {
                // If it's an array, we assume it's already in the correct format
                return $styleString;
            }

            // If it's not a string or an array, return an empty array
            return [];
        }

        $result = [];

        // 1. Split the string by semicolons to get individual "property:value" items
        // Remove leading and trailing whitespace, then split
        $declarations = array_filter(
            array_map('trim', explode(';', $styleString))
        );

        foreach ($declarations as $decl) {
            // 2. Split each declaration at the first colon
            $parts = explode(':', $decl, 2);
            if (count($parts) !== 2) {
                // Invalid declaration (no colon), skip it
                continue;
            }

            // 3. Trim property name and value
            $property = trim($parts[0]);
            $value = trim($parts[1]);

            // 4. Assign to result array; later declarations override earlier ones
            $result[$property] = $value;
        }

        return $result;
    }
}
