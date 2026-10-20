<?php

namespace Carbon\Eel\EelHelper;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Traversable;

class AlpineJSHelper implements ProtectedContextAwareInterface
{
    const EXPRESSION_PREFIX = '__EXPRESSION__';

    /**
     * @var bool
     * @Flow\InjectConfiguration(path="useTemplateLiteralsForAlpineJS")
     */
    protected $useTemplateLiteralsForAlpineJS;

    /**
     * @var array
     * @Flow\InjectConfiguration(path="magicFunctionsNames")
     */
    protected $magicFunctionsNames;

    /**
     * @deprecated Please use AlpineJS.function instead
     * Generate a function call for AlpineJS  Example: x-data="name(arg1, arg2, ..argN)" https://alpinejs.dev/globals/alpine-data
     *
     * @param string $name function name
     * @param iterable|mixed $arguments
     * @return string
     */
    public function xData(string $name, mixed ...$arguments): string
    {
        return $this->function($name, ...$arguments);
    }

    /**
     * Generate a function call for AlpineJS. Example: x-data="name(arg1, arg2, ..argN)" https://alpinejs.dev/globals/alpine-data
     *
     * @param string $name function name
     * @param iterable|mixed $arguments
     * @return string
     */
    public function function(string $name, mixed ...$arguments): string
    {
        $result = [];
        foreach ($arguments as $argument) {
            if (is_array($argument) || $argument instanceof Traversable) {
                $result[] = $this->arrayToString($argument, false);
                continue;
            }

            $result[] = $this->returnValue($argument, true);
        }

        return sprintf('%s(%s)', $name, implode(',', $result));
    }

    /**
     * Generate an object for AlpineJS x-data="{name: arg1, name2: arg2, ..nameN: argN}" https://alpinejs.dev/directives/data
     *
     * @param array|iterable $array
     * @return string
     */
    public function object(iterable $array): ?string
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }

        if (!is_array($array)) {
            return null;
        }

        return $this->keyedArrayToString($array, false);
    }

    /**
     * Generate a function call for AlpineJS magics
     *
     * @param string $name The name of the magic function
     * @param iterable|mixed $arguments
     * @return string
     */
    public function magic(string $name, mixed ...$arguments)
    {
        if (!str_starts_with($name, '$')) {
            $name = '$' . $name;
        }

        return $this->function($name, ...$arguments);
    }

    /**
     * Use this to pass a javascript expression inside of the `AlpineJS.object`, `AlpineJS.function` or `AlpineJS.magic` helper
     *
     * @param string $value
     * @return string
     */
    public function expression($value): string
    {
        return sprintf('%s%s', self::EXPRESSION_PREFIX, $value);
    }

    /**
     * Return values for the function `function`
     *
     * @param mixed $value
     * @param bool $returnNull
     * @param string|null $key The key associated with the value, if applicable
     * @return mixed
     */
    private function returnValue($value, $returnNull = false, ?string $key = null)
    {
        $valueIsString = is_string($value);

        if ($valueIsString && str_starts_with($value, self::EXPRESSION_PREFIX)) {
            return substr($value, strlen(self::EXPRESSION_PREFIX));
        }

        // Handle magic functions based on the configuration
        if ($valueIsString && str_ends_with($value, ')')) {
            foreach ($this->magicFunctionsNames as $magicFunction => $enabled) {
                if ($enabled && str_starts_with($value, '$' . $magicFunction . '(')) {
                    return $value;
                }
            }
        }

        if (is_null($value)) {
            if ($returnNull) {
                return 'null';
            }
            return null;
        }

        if (is_numeric($value)) {
            return $value;
        }

        if ($valueIsString) {
            if ($this->isJavascriptFunction($key, $value)) {
                return $value;
            }
            if ($this->useTemplateLiteralsForAlpineJS) {
                return sprintf("`%s`", $value);
            }
            // escape single quotes in the string
            $value = str_replace("'", "\\'", $value);
            return sprintf("'%s'", $value);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }

    /**
     * Generates a string from an array with keys
     *
     * @param iterable|array $array
     * @param bool $returnNull
     * @return string
     */
    private function keyedArrayToString($array, $returnNull = false): string
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }

        $result = [];

        /**
         * @var array $array
         */
        foreach ($array as $key => $value) {
            if (is_array($value) || $value instanceof Traversable) {
                $result[] =  $this->getKeyAndValue($key, $this->arrayToString($value, $returnNull));
                continue;
            }

            $value = $this->returnValue($value, $returnNull, $key);

            if (is_null($value)) {
                if (!$returnNull) {
                    continue;
                }

                $value = 'null';
            }

            $result[] = $this->getKeyAndValue($key, $value);
        }
        return sprintf('{%s}', implode(',', $result));
    }

    /**
     * Check if the key and value are a javascript function
     *
     * @param string|null $key
     * @param mixed $value
     * @return boolean
     */
    private function isJavascriptFunction(?string $key = null, mixed $value = null): bool
    {
        if (empty($key) || empty($value) || !is_string($value)) {
            return false;
        }
        $isMethod = preg_match('/^[a-zA-Z0-9]+\(.*\)$/', $key, $matches);
        if (!$isMethod) {
            return false;
        }
        return str_starts_with($value, '{') && str_ends_with($value, '}');
    }

    /**
     * Get the key for the array
     *
     * @param string $key
     * @param string $value
     * @return string
     */
    private function getKeyAndValue(string $key, string $value): string
    {
        if ($this->isJavascriptFunction($key, $value)) {
            return $key . $value;
        }

        if (str_contains($key, '-')) {
            $key = sprintf("'%s'", $key);
        }

        return $key . ':' . $value;
    }

    /**
     * Generates a string from an array
     *
     * @param iterable|array $array
     * @param bool $returnNull
     * @return string
     */
    private function arrayToString($array, $returnNull = false): string
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }

        $result = [];

        /**
         * @var array $array
         */
        if (array_is_list($array)) {
            foreach ($array as $value) {
                if (is_array($value) || $value instanceof Traversable) {
                    $result[] = $this->arrayToString($value, $returnNull);
                    continue;
                }

                $result[] = $this->returnValue($value, true);
            }

            return sprintf('[%s]', implode(',', $result));
        }

        return $this->keyedArrayToString($array, $returnNull);
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
