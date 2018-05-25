<?php

namespace Carbon\Eel\Eel\Helper;

use Neos\Eel\EvaluationException;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;

/**
 * Helpers for Eel contexts
 *
 * @Flow\Proxy(false)
 */
class StringHelper implements ProtectedContextAwareInterface
{
    /**
     * Helper to convert `CamelCaseStrings` to `hyphen-case-strings`
     *
     *
     * Examples::
     *
     *     Carbon.String.convertCamelCase('HelloWorld') == 'hello-world'
     *     Carbon.String.convertCamelCase('HelloWorld', '_') == 'hello_world'
     *     Carbon.String.convertCamelCase('HelloWorld', '') == 'helloworld'
     *
     * @param string $string A string
     * @param string $seperator The seperator
     * @return string The converted string
     */
    public function convertCamelCase($string, $seperator = '-')
    {
        $string = (string)$string;
        $seperator = (string)$seperator;

        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1' . $seperator, $string));
    }

    /**
     * Helper to make sure we got a string back
     *
     *
     * Examples::
     *
     *     Carbon.String.convertToString(' helloworld  ') == 'helloworld'
     *     Carbon.String.convertToString([' hello', ' world']) == 'hello world'
     *     Carbon.String.convertToString(['hello', 'world'], '-') == 'hello-world'
     *
     * @param string|array $input A string or an array
     * @param string $seperator The $seperator
     * @return string The converted string
     */
    public function convertToString($input, $seperator = ' ')
    {
        $seperator = (string)$seperator;
        $string = is_array($input) ? implode($seperator, $input) : (string)$input;

        // Remove double space and trim the string
        return trim(preg_replace('/(\s)+/', ' ', $string));
    }

    /**
     * Replace all newlines with an <br>
     *
     *
     * Examples::
     *
     *     Carbon.String.nl2br('hello\nworld') == 'hello<br>world'
     *     Carbon.String.nl2br('hello\nworld', ' | ') == 'hello | world'
     *
     * @param string $string A string
     * @param string $seperator The $seperator
     * @return string The converted string
     */
    public function nl2br($string, $seperator = '<br>')
    {
        $string = (string)$string;
        $seperator = (string)$seperator;

        // Remove double space and trim the string
        return preg_replace('/\n/', $seperator, trim($string));
    }

    /**
     * Replace non-breaking spaces and double spaces with a normal space
     *
     *
     * Examples::
     *
     *     Carbon.String.removeNbsp('hello world') == 'hello world'
     *     Carbon.String.removeNbsp('hello   world') == 'hello world'
     *
     * @param string $string A string
     * @return string The converted string
     */
    public function removeNbsp($string)
    {
        $string = (string)$string;
        $space = ' ';

        return trim(preg_replace('/\s\s+/', $space, str_replace(' ', $space, $string)));
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
