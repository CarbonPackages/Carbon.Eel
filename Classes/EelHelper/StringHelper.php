<?php

namespace Carbon\Eel\EelHelper;

use Behat\Transliterator\Transliterator;
use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;
use Carbon\Eel\Service\BEMService;

/**
 * @Flow\Proxy(false)
 */
class StringHelper implements ProtectedContextAwareInterface
{

    /**
     * Generates a BEM string
     *
     * @param string       $block     defaults to null
     * @param string       $element   defaults to null
     * @param string|array $modifiers defaults to []
     *
     * @return string
     */
    public function BEM($block = null, $element = null, $modifiers = []): string
    {
        return BEMService::getClassNamesString($block, $element, $modifiers);
    }

    /**
     * Generates a slug of the given string
     *
     * @param string $string The string
     *
     * @return string
     */
    public function urlize(string $string): string
    {
        return Transliterator::urlize($string);
    }

    /**
     * Helper to convert strings to PascalCase
     *
     * @param string $string A string
     *
     * @return string The converted string
     */
    public function toPascalCase(string $string): string
    {
        $string = Transliterator::urlize((string) $string);
        $string = str_replace('-', '', ucwords($string, '-'));

        return $string;
    }

    /**
     * Helper to convert strings to camelCase
     *
     * @param string $string A string
     *
     * @return string The converted string
     */
    public function toCamelCase(string $string): string
    {
        return lcfirst($this->toPascalCase($string));
    }

    /**
     * Replace occurrences of a search string inside the string using regular expression matching (PREG style)
     *
     * Examples::
     *
     *     String.pregReplace("Some.String with sp:cial characters", "/[[:^alnum:]]/", "-") == "Some-String-with-sp-cial-characters"
     *     String.pregReplace("2016-08-31", "/([0-9]+)-([0-9]+)-([0-9]+)/", "$3.$2.$1") == "31.08.2016"
     *
     * @param string $string The input string
     * @param string $pattern A PREG pattern
     * @param string $replace A replacement string, can contain references to capture groups with "\\n" or "$n"
     * @param integer $limit The maximum possible replacements for each pattern in each subject string. Defaults to -1 (no limit).
     * @return string The string with all occurrences replaced
     */

    public function pregReplace(string $string, string $pattern, string $replace, int $limit = -1): string
    {
        return preg_replace($pattern, $replace, (string) $string, $limit);
    }

    /**
     * Helper to convert `CamelCaseStrings` to `hyphen-case-strings`
     *
     * Examples:
     *
     *     Carbon.String.convertCamelCase('HelloWorld') == 'hello-world'
     *     Carbon.String.convertCamelCase('HelloWorld', '_') == 'hello_world'
     *     Carbon.String.convertCamelCase('HelloWorld', '') == 'helloworld'
     *
     * @param string $string    A string
     * @param string $separator The separator
     *
     * @return string The converted string
     */
    public function convertCamelCase($string, $separator = '-'): string
    {
        $string = (string) $string;
        $separator = (string) $separator;

        return strtolower(
            preg_replace(
                '/([a-zA-Z])(?=[A-Z])/',
                '$1' . $separator,
                $string
            )
        );
    }

    /**
     * Helper to make sure we got a string back
     *
     * Examples:
     *
     *     Carbon.String.convertToString(' helloworld  ') == 'helloworld'
     *     Carbon.String.convertToString([' hello', ' world']) == 'hello world'
     *     Carbon.String.convertToString(['hello', 'world'], '-') == 'hello-world'
     *
     * @param string|array $input     A string or an array
     * @param string       $separator The $separator
     *
     * @return string The converted string
     */
    public function convertToString($input, $separator = ' '): string
    {
        $separator = (string) $separator;
        $string = is_array($input) ? implode($separator, $input) : (string) $input;

        // Remove double space and trim the string
        return trim(preg_replace('/(\s)+/', ' ', $string));
    }

    /**
     * Replace all newlines with an <br>
     *
     * Examples:
     *
     *     Carbon.String.nl2br('hello\nworld') == 'hello<br>world'
     *     Carbon.String.nl2br('hello\nworld', ' | ') == 'hello | world'
     *
     * @param string $string    A string
     * @param string $separator The separator
     *
     * @return string The converted string
     */
    public function nl2br($string, $separator = '<br>'): string
    {
        $string = (string) $string;
        $separator = (string) $separator;

        // Remove double space and trim the string
        return preg_replace('/\n/', $separator, trim($string));
    }

    /**
     * Merge strings and arrays to a string with unique values, separated by an empty space
     *
     * @param iterable|mixed $mixed_ Optional variable list of arrays / values
     * @return string|null The merged string
     */
    public function merge($mixed_ = null): ?string
    {
        $arguments = func_get_args();
        $explode = function ($value) {
            return explode(' ', $value);
        };

        // Create an array with trimmed values
        foreach ($arguments as &$argument) {
            if ($argument instanceof \Traversable) {
                $argument = iterator_to_array($argument);
            }
            if (is_array($argument)) {
                // Clean up array to remove later double entries
                $argument = array_map($explode, $argument);
                $resultArray = [];
                foreach ($argument as $element) {
                    if (is_array($element)) {
                        foreach ($element as $subElement) {
                            $resultArray[] = $subElement;
                        }
                    } else {
                        $resultArray[] = $element;
                    }
                }
                $argument = $resultArray;
            }
            if (is_string($argument)) {
                $argument = explode(' ', $argument);
            } elseif (!is_array($argument)) {
                $argument = [null];
            }
            $argument = array_map('trim', array_filter($argument));
        }
        $mergedArray = array_unique(array_merge(...$arguments));

        if (count($mergedArray)) {
            return implode(' ', $mergedArray);
        }

        return null;
    }

    /**
     * Replace non-breaking spaces and double spaces with a normal space
     *
     * Examples:
     *
     *     Carbon.String.removeNbsp('hello world') == 'hello world'
     *     Carbon.String.removeNbsp('hello   world') == 'hello world'
     *
     * @param string $string A string
     *
     * @return string The converted string
     */
    public function removeNbsp($string): string
    {
        $space = ' ';
        $string = (string) str_replace('&nbsp;', $space, $string);

        return trim(
            preg_replace(
                '/\s\s+/',
                $space,
                str_replace(
                    ' ',
                    $space,
                    $string
                )
            )
        );
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
