<?php

namespace Carbon\Eel\Service;

class StringConversionService
{
    /**
     * Make sure we got a string back
     *
     * Examples:
     *
     *     ' helloworld  ' => 'helloworld'
     *     [' hello', ' world'] => 'hello world'
     *     ['hello', 'world'], '-' => 'hello-world'
     *
     * @param string|array $input     A string or an array
     * @param string       $separator The $separator
     * @return string The converted string
     */
    public static function convertToString($input, $separator = ' '): string
    {
        $separator = (string) $separator;
        $string = is_array($input) ? implode($separator, $input) : (string) $input;

        // Remove double space and trim the string
        return trim(preg_replace('/(\s)+/', ' ', $string));
    }

    /**
     * Convert strings to PascalCase
     *
     * @param string $string A string
     * @return string The converted string
     */
    public static function toPascalCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    /**
     * Convert strings to camelCase
     *
     * @param string $string A string
     * @return string The converted string
     */
    public static function toCamelCase(string $string): string
    {
        return lcfirst(self::toPascalCase($string));
    }


    /**
     * Convert `CamelCaseStrings` to `hyphen-case-strings`
     *
     * Examples:
     *
     *     'HelloWorld' => 'hello-world'
     *     'HelloWorld', '_' => 'hello_world'
     *     'HelloWorld', '' => 'helloworld'
     *
     * @param string $string    A string
     * @param string $separator The separator
     * @return string The converted string
     */
    public static function convertCamelCase($string, $separator = '-'): string
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
     * Make every word title case. Splits by uppercase letters, - and _
     *
     * @param string|null $string
     * @return string
     */
    public static function titleCaseWords(?string $string = null): string
    {
        if (!$string) {
            return '';
        }
        // Replace -/_/. with a space
        $string = str_replace(['-', '_', '.'], ' ', $string);
        // Place before each uppercase letter a space
        $string = implode(' ', preg_split('/(?=[A-Z])/', $string));
        // Remove double space and trim the string
        $string = trim(preg_replace('/(\s)+/', ' ', $string));
        // Every word should be title case
        return ucwords($string);
    }
}
