<?php

namespace Carbon\Eel\EelHelper;

use Behat\Transliterator\Transliterator;
use Carbon\Eel\Service\BEMService;
use Carbon\Eel\Service\MergeClassesService;
use MatthiasMullie\Minify;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Eel\EvaluationException;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Cryptography\HashService;
use Neos\Flow\Validation\Validator\EmailAddressValidator;
use Traversable;
use function implode;
use function is_array;
use function lcfirst;
use function preg_match_all;
use function preg_last_error;
use function preg_replace;
use function str_replace;
use function strtolower;
use function trim;
use function ucwords;

class StringHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var HashService
     */
    protected $hashService;

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
     * Generate a hash (HMAC) for a given string
     *
     * @param string $string The string for which a hash should be generated
     * @return string The hash of the string
     * @throws InvalidArgumentForHashGenerationException if something else than a string was given as parameter
     */
    public function generateHmac($string): string
    {
        return $this->hashService->generateHmac((string) $string);
    }

    /**
     * Tests if a string $string matches the HMAC given by $hash.
     *
     * @param string $string The string which should be validated
     * @param string $hmac The hash of the string
     * @return boolean true if string and hash fit together, false otherwise.
     */
    public function validateHmac($string, $hmac)
    {
        return ($this->generateHmac($string) === $hmac);
    }

    /**
     * Minify JavaScript
     *
     * @param string $javascript The JavaScript string
     * @return string
     */
    public function minifyJS(string $javascript): string
    {
        $minifier = new Minify\JS($javascript);
        return $minifier->minify();
    }

    /**
     * Generate a heading tag name name based on the given tag name and modifier
     *
     * @param string $tagName The tag name
     * @param int    $modifier The modifier
     * @return string
     */
    public function heading(string $tagName, int $modifier = 1): string
    {
        if ($modifier === 0) {
            return $tagName;
        }
        $tagName = strtolower($tagName);
        $maxHeadings = 6;

        if ($tagName === 'p') {
            if ($modifier > 0) {
                return 'p';
            }
            $type = $maxHeadings - abs($modifier) + 1;
            if ($type > $maxHeadings) {
                $type = $maxHeadings;
            }
            return 'h' . max($type, 1);
        }

        if (!preg_match('/^h[1-6]$/', $tagName)) {
            return $tagName;
        }

        // Get the number of the heading
        $number = (int)substr($tagName, 1);
        $type = $number + $modifier;
        if ($type > $maxHeadings) {
            return 'p';
        }
        return 'h' . max($type, 1);
    }

    /**
     * Minify CSS
     *
     * @param string $css The CSS string
     * @return string
     */
    public function minifyCSS(string $css): string
    {
        $minifier = new Minify\CSS($css);
        return $minifier->minify();
    }

    /**
     * Get a property from a node link
     *
     * @param NodeInterface|null $node
     * @param string|null        $value
     * @param string             $propertyName
     * @param mixed              $fallback
     * @return mixed
     */
    public function getPropertyFromNodeLink(?NodeInterface $node, ?string $value, string $propertyName = 'title', $fallback = null)
    {
        if (!$node || !$value || !$propertyName || !str_starts_with($value, 'node://')) {
            return $fallback;
        }
        $nodeId = str_replace('node://', '#', $value);
        $fQ = new FlowQuery([$node]);
        $targetNode = $fQ->find($nodeId)->get(0);
        if (!$targetNode) {
            return $fallback;
        }
        return $targetNode->getProperty($propertyName) ?? $fallback;
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
     * Checks if the string is a valid email address
     *
     * @param string|null $email
     * @return boolean
     */
    public function isValidEmail(?string $email = null)
    {
        if (!is_string($email)) {
            return false;
        }

        $validator = new EmailAddressValidator();
        return $validator->validate($email)->hasErrors() === false;
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
     * Helper to replace the first occurrence of a string
     *
     * @param string $string The string being searched and replaced on
     * @param string $search he value being searched for
     * @param string|null $replace The replacement value that replaces found search value
     * @return string
     */
    public function replaceOnce(string $string, string $search, ?string $replace = null): string
    {
        $pos = strpos($string, $search);
        if ($pos === false) {
            return $string;
        }

        if (!isset($replace)) {
            $replace = '';
        }

        return substr_replace($string, $replace, $pos, strlen($search));
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
     * Make every word title case. Splits by uppercase letters, - and _
     *
     * @param string|null $string
     * @return string
     */
    public function titleCaseWords(?string $string = null): string
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

    /**
     * @deprecated Please use Carbon.String.classNames instead
     *
     * @param iterable|mixed $arguments Optional variable list of arrays / values
     * @return string|null The merged string
     */
    public function merge(...$arguments): ?string
    {
        return MergeClassesService::merge(...$arguments);
    }

    /**
     * Merge strings and arrays to a string with unique values, separated by an empty space
     *
     * @param iterable|mixed $arguments Optional variable list of arrays / values
     * @return string|null The merged string
     */
    public function classNames(...$arguments): ?string
    {
        return MergeClassesService::merge(...$arguments);
    }

    /**
     * Generate styles from the given arguments
     *
     * @param iterable|mixed $arguments
     * @return string|null The merged string
     */
    public function styles(...$arguments): ?string
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
            }
        }

        $result = [];
        foreach ($keyedResult as $key => $value) {
            $key = $this->convertCamelCase($key, '-');
            $result[] = sprintf('%s:%s;', $key, $value);
        }

        if (count($result)) {
            return implode('', $result);
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
        // Remove zeros in brackets
        $phoneNumber = str_replace('(0)', '', $phoneNumber);

        // Replace + width 00
        $phoneNumber = str_replace('+', '00', (string)$phoneNumber);

        // Remove all non numeric characters
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

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
