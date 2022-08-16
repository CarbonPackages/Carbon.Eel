<?php

namespace Carbon\Eel\EelHelper;

use Behat\Transliterator\Transliterator;
use Carbon\Eel\Service\BEMService;
use Neos\Eel\EvaluationException;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Traversable;
use function array_filter;
use function array_map;
use function array_merge;
use function array_unique;
use function array_walk_recursive;
use function count;
use function explode;
use function func_get_args;
use function implode;
use function is_array;
use function iterator_to_array;
use function lcfirst;
use function preg_match_all;
use function preg_last_error;
use function preg_replace;
use function str_replace;
use function strtolower;
use function trim;
use function ucwords;

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
     * @return string|null
     */
    public function BEM($block = null, $element = null, $modifiers = []): ?string
    {
        return BEMService::getClassNamesString($block, $element, $modifiers);
    }

    /**
     * Generates a slug of the given string
     *
     * @param string $string The string
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
     * @return string The converted string
     */
    public function toCamelCase(string $string): string
    {
        return lcfirst($this->toPascalCase($string));
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
     * Flatten an argument from the merge function
     *
     * @param mixed $value
     * @return array
     */
    private function flattenMergeArgument($value): array
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
            array_walk_recursive($value, function ($a) use (&$return) {
                if ($a === true) {
                    $a = ['true'];
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

    /**
     * Merge strings and arrays to a string with unique values, separated by an empty space
     *
     * @param iterable|mixed $mixed_ Optional variable list of arrays / values
     * @return string|null The merged string
     */
    public function merge($mixed_ = null): ?string
    {
        $arguments = func_get_args();

        // Create an array with trimmed values
        foreach ($arguments as &$argument) {
            $argument = $this->flattenMergeArgument($argument);
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
     * Split a string into an array width integers and strings. Useful for animations
     *
     * @param integer|string $string
     * @return array An array width integer and strings
     * @throws EvaluationException
     */
    public function splitIntegerAndString($string): array
    {

        $number = preg_match_all("/(\d+)|(\D+)/", (string)$string, $matches);
        if ($number === false) {
            throw new EvaluationException(
                'Error evaluating regular expression for splitIntegerAndString: ' . preg_last_error(),
                1642686832
            );
        }
        if ($number === 0) {
            return [];
        }
        $array = [];
        foreach ($matches[0] as $value) {
            if (is_numeric($value)) {
                $array[] = (int)$value;
            } else {
                $array[] = $value;
            }
        }
        return $array;
    }

    /**
     * Helper to convert phone numbers to a compatible format for links
     *
     * @param string $phoneNumber
     * @param string|null $defaultCountryCode
     * @param string|null $prefix defaults to tel:
     * @return string
     */
    public function phone(
        string $phoneNumber,
        ?string $defaultCountryCode = null,
        ?string $prefix = 'tel:'
    ): ?string {

        // Remove Slashes
        $phoneNumber = str_replace('/', '', (string)$phoneNumber);

        // Remove all spaces
        $phoneNumber = preg_replace('/\s/', '', $phoneNumber);

        // Remove zeros in brackets
        $phoneNumber = str_replace('(0)', '', $phoneNumber);

        // If nothing is left, return null
        if (!strlen($phoneNumber)) {
            return null;
        }

        // Make local number international
        if (isset($defaultCountryCode)) {
            $phoneNumber = preg_replace(
                '/^0([1-9])/',
                $defaultCountryCode . '$1',
                $phoneNumber
            );
        }

        // Replace + width 00
        $phoneNumber = str_replace('+', '00', (string)$phoneNumber);

        // Add prefix
        if (isset($prefix)) {
            return $prefix . $phoneNumber;
        }

        return $phoneNumber;
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
