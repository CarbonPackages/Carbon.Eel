<?php

namespace Carbon\Eel;

use Behat\Transliterator\Transliterator;
use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;
use Carbon\Eel\ArrayHelper;

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
        return implode(" ", ArrayHelper::BEM($block, $element, $modifiers));
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
        $string = (string)$string;
        $separator = (string)$separator;

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
        $separator = (string)$separator;
        $string = is_array($input) ? implode($separator, $input) : (string)$input;

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
        $string = (string)$string;
        $separator = (string)$separator;

        // Remove double space and trim the string
        return preg_replace('/\n/', $separator, trim($string));
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
        $string = (string)$string;
        $space = ' ';

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
