[![Latest stable version]][packagist] [![Total downloads]][packagist] [![License]][licensefile] [![GitHub forks]][fork] [![GitHub stars]][stargazers] [![GitHub watchers]][subscription]

# Carbon.Eel Package for [Neos CMS]

## BEM Helper

Generates BEM classes. The modifiers property can be a string (for one modifier), an array (e.g. `['one', 'two']`), or an array with keys and values. If you have an array with keys and values (like a Fusion DataStructure) and a value is `true`, the key's name gets used for the modifier.

-   `block` (string, required) The name of the block
-   `element` (string) The name of the element, optional
-   `modifiers` (string|array) The name of the modifiers, optional

### `BEM.array(block, element, modifiers)`

Shortcut to `Carbon.Array.BEM(block, element, modifiers)`

### `BEM.string(block, element, modifiers)`

Shortcut to `Carbon.String.BEM(block, element, modifiers)`

### `BEM.modifier(class, modifiers)`

Generates a string with BEM classes. The modifiers property can be a string (for one modifier), an array (e.g. `['one', 'two']`), or an array with keys and values. If you have an array with keys and values (like a Fusion DataStructure) and the value is `true`, the key's name gets used for the modifier.

-   `class` (string, required) The name of the class
-   `modifiers` (string|array) The name of the modifiers, optional

**Return** The string

## Array Helper

### `Carbon.Array.BEM(block, element, modifiers)`

Generates an array with BEM classes. The modifiers property can be a string (for one modifier), an array (e.g. `['one', 'two']`), or an array with keys and values. If you have an array with keys and values (like a Fusion DataStructure) and the value is `true`, the key's name gets used for the modifier.

-   `block` (string, required) The name of the block
-   `element` (string) The name of the element, optional
-   `modifiers` (string|array) The name of the modifiers, optional

**Return** The array

### `Carbon.Array.setKeyValue(array, key, value)`

It can be used to add a value with a dynamic key.

Example:

```elm
array = Neos.Fusion:RawArray
array.@process.addKeyValue = ${Carbon.Array.setKeyValue(value, 'key', 'value')}
```

-   `array` (array) The array which should be extended
-   `key` (string) The key for the entry
-   `value` (mixed) The value

**Return** The extended array

### `Carbon.Array.ksort(array)`

Sort an array by key

Example:

```elm
array.@process.ksort = ${Carbon.Array.ksort(value)}
```

-   `array` (array) The array which should be sorted

**Return** The sorted array

### `Carbon.Array.filter(array)`

Iterates over each value in the array, and all entries of the array equal to `false` will be removed.

Example:

```elm
array.@process.filter = ${Carbon.Array.filter(value)}
```

-   `array` (array) The array to iterate over

**Return** The filtered array

### `Carbon.Array.values(array)`

Return all the values of an array

Example:

```elm
array.@process.values = ${Carbon.Array.values(value)}
```

-   `array` (array) The array to convert

**Return** An indexed array of values.

### `Carbon.Array.join(array, separator)`

Join the given array recursively using the given separator.

```elm
${Carbon.Array.join(array, ',')}
```

-   `array` (array) The array that should be processed
-   `separator` (string, optional) The separator between the values defaults to `,`

**Return** The converted array as a string

### `Carbon.Array.extractSubElements(array, preserveKeys)`

This method extracts sub-elements to the parent level.

An input array of type:

```
[
 element1 => [
   0 => 'value1'
 ],
 element2 => [
   0 => 'value2'
   1 => 'value3'
 ],
```

will be converted to:

```
[
   0 => 'value1'
   1 => 'value2'
   2 => 'value3'
]
```

-   `array` (array) The array that should be processed
-   `preserveKeys` (boolean, optional) Option if the key should be preserved, defaults to `false`

**Return** The converted array

### `Carbon.Array.unique(array, filter)`

Removes duplicate values from an array

Example:

```elm
array.@process.removeDuplicates = ${Carbon.Array.unique(value)}
```

-   `array` (array) The input array
-   `filter` (boolean, optional) Option if the array should be filtered, defaults to `false`

**Return** Returns the filtered array.

## FileContent Helper

### `Carbon.FileContent.path(string)`

Returns the file content of a path. Fails silently.

Examples:

```elm
Carbon.FileContent.path('resource://Foo.Bar/Private/Assets/Logo.svg')
Carbon.FileContent.path('Foo.Bar/Private/Assets/Logo.svg')
```

-   `string` (string) The path to the file

**Return** The content of the file

### `Carbon.FileContent.pathHash(string, length)`

Returns the hash value from the file content of a path. Fails silently.

Examples:

```elm
Carbon.FileContent.pathHash('resource://Foo.Bar/Private/Assets/Logo.svg') == 1d62f5a5
Carbon.FileContent.pathHash('Foo.Bar/Private/Assets/Logo.svg', 20) == 1d62f5a55ad5e304d60d
```

-   `string` (string) The path to the file
-   `length` (integer, optional) The length of the hash value defaults to `8`. The maximal value is `40`

**Return** The hash value from the content of the file

### `Carbon.FileContent.resource(resource)`

Returns the file content of a persisted resource. Fails silently.

Example:

```elm
Carbon.FileContent.resource(q(node).property('file'))
```

-   `resource` (resource) The persisted resource to read

**Return** The content of the file

### `Carbon.FileContent.resourceHash(resource, length)`

Returns the hash value from the file content of a persisted resource. Fails silently.

Example:

```elm
Carbon.FileContent.resourceHash(q(node).property('file')) == 1d62f5a5
Carbon.FileContent.resourceHash(q(node).property('file'), 20) == 1d62f5a55ad5e304d60d
```

-   `resource` (resource) The persisted resource to read
-   `length` (integer, optional) The length of the hash value defaults to `8`. The maximal value is `40`

**Return** The hash value from the content of the file

## String Helper

### `Carbon.String.BEM(block, element, modifiers)`

Generates a string with BEM classes. The modifiers property can be a string (for one modifier), an array (e.g. `['one', 'two']`), or an array with keys and values. If you have an array with keys and values (like a Fusion DataStructure) and the value is `true`, the key's name gets used for the modifier.

-   `block` (string, required) The name of the block
-   `element` (string) The name of the element, optional
-   `modifiers` (string|array) The name of the modifiers, optional

**Return** The string

### `Carbon.String.urlize(string)`

Generates a slug of the given string

Examples:

```elm
Carbon.String.urlize('Hello World') == 'hello-world'
Carbon.String.urlize('Ä_ÖÜ äöü') == 'ae-oeue-aeoeue'
```

-   `string` (string) The string to convert

**Return** The converted string

### `Carbon.String.toPascalCase(string)`

Convert strings to `PascalCase`.

Examples:

```elm
Carbon.String.toPascalCase('hello-world') == 'HelloWorld'
Carbon.String.toPascalCase('hello world') == 'HelloWorld'
Carbon.String.toPascalCase('Hello World') == 'HelloWorld'
```

-   `string` (string) The string to convert

**Return** The converted string

### `Carbon.String.toCamelCase(string)`

Convert strings to `camelCase`.

Examples:

```elm
Carbon.String.toCamelCase('hello-world') == 'helloWorld'
Carbon.String.toCamelCase('hello world') == 'helloWorld'
Carbon.String.toCamelCase('Hello World') == 'helloWorld'
```

-   `string` (string) The string to convert

**Return** The converted string

### `Carbon.String.convertCamelCase(string, separator)`

Convert `CamelCaseStrings` to `hyphen-case-strings`

Examples:

```elm
Carbon.String.convertCamelCase('HelloWorld') == 'hello-world'
Carbon.String.convertCamelCase('HelloWorld', '_') == 'hello_world'
Carbon.String.convertCamelCase('HelloWorld', '') == 'helloworld'
```

-   `string` (string) The string to convert
-   `separator` (string, optional) The separator between the words defaults to `-`

**Return** The converted string

### `Carbon.String.convertToString(input, separator)`

Helper to make sure to get a string back.

Examples:

```elm
Carbon.String.convertToString(' helloworld  ') == 'helloworld'
Carbon.String.convertToString([' hello', ' world']) == 'hello world'
Carbon.String.convertToString(['hello', 'world'], '-') == 'hello-world'
```

-   `input` (string, array) A string or an array to convert
-   `separator` (string, optional) The separator between the words, defaults to whitespace

**Return** The converted string

### `Carbon.String.nl2br(string, separator)`

Replace all newlines with an `<br>`.

Examples:

```elm
Carbon.String.nl2br('hello\nworld') == 'hello<br>world'
Carbon.String.nl2br('hello\nworld', ' | ') == 'hello | world'
```

-   `string` (string) A string to convert
-   `separator` (string, optional) The separator between the words, defaults to `<br>`

**Return** The converted string

### `Carbon.String.removeNbsp(string)`

Replace non-breaking spaces and double spaces with a normal space.

Examples:

```elm
Carbon.String.removeNbsp(' hello world') == 'hello world'
Carbon.String.removeNbsp('hello   world') == 'hello world'
```

-   `string` (string) A string to convert

**Return** The converted string

### `Carbon.String.pregReplace(string, pattern, replace, limit)`

Replace occurrences of a search string inside the string using regular expression matching (PREG style).

-   `string` (string) The input string
-   `pattern` (string) A PREG pattern
-   `replace` (string) A replacement string, can contain references to capture groups with "\\n" or "\$n"
-   `limit` (integer) The maximum possible replacements for each pattern in each subject string. Defaults to -1 (no limit).

**Return** The string with all occurrences replaced

### `Carbon.String.merge(mixed1, mixed2, mixedN)`

Merge strings and arrays to a string with unique values, separated by an empty space.

Examples:

| Expression                                                | Result                 |
| --------------------------------------------------------- | ---------------------- |
| `Carbon.String.merge('', 'one')`                          | `'one'`                |
| `Carbon.String.merge(['one two three'], ['one', 'four'])` | `'one two three four'` |
| `Carbon.String.merge(null, null)`                         | `null`                 |
| `Carbon.String.merge('one two three', ['one', 'four']`    | `'one two three four'` |

**Return** The merged string

## Number Helper

### `Carbon.Number.format(number, decimals, dec_point, thousands_sep)`

Format a number with grouped thousands. If `decimals` is set to `null`, it returns as many as needed decimals.

-   `number` (float, required) The number being formatted
-   `decimals` (integer, optional) Sets the number of decimal points, defaults to `null`
-   `dec_point` (string, optional) The name of the modifier defaults to `.`
-   `thousands_sep` (string, optional) The name of the modifier defaults to `,`

### `Carbon.Number.formatLocale(number, decimals, locale)`

Format a localized number with grouped thousands. If `decimals` is set to `null`, it returns as many as needed decimals.

-   `number` (float, required) The number being formatted
-   `decimals` (integer, optional) Sets the number of decimal points, defaults to `null`
-   `locale` (string, optional) String locale - example (de_DE|en|ru_RU)

### `Carbon.Number.decimalDigits(number)`

Get number of decimal digits.

-   `number` (float, required) The number being formatted

## Backend Helper

### `Carbon.Backend.language()`

Returns the language from the interface

### `Carbon.Backend.translate(id, originalLabel, arguments, source, package, quantity, locale)`

Get the translated value (in the language of the interface) for an id or original label. If the only id is set and contains a translation shorthand string, translate according to that shorthand.

In all other cases:  
Replace all placeholders with corresponding values if they exist in the translated label.

-   `id` (string) Id to use for finding translation (trans-unit id in XLIFF)
-   `originalLabel` (string, optional) The original translation value (the untranslated source string)
-   `arguments` (array, optional) Array of numerically indexed or named values to be inserted into placeholders
-   `source` (string, optional) Name of file with translations
-   `package` (string, optional) Target package key
-   `quantity` (mixed, optional) A number to find a plural form for (float or int), `null` to not use plural forms
-   `locale` (string, optional) An identifier of the locale to use (NULL for use the interface language)

Returns the translated label or source label / ID key

## Installation

Carbon.Eel is available via packagist. Just run

```bash
composer require carbon/eel
```

To install the package under Neos 2.\* / Flow 3.\* you have to enter

```bash
composer require "carbon/eel:^0.5"
```

## Credits

Some of the Eel helpers were inspired and or copied from [punkt.de]

[packagist]: https://packagist.org/packages/carbon/eel
[latest stable version]: https://poser.pugx.org/carbon/eel/v/stable
[total downloads]: https://poser.pugx.org/carbon/eel/downloads
[license]: https://poser.pugx.org/carbon/eel/license
[github forks]: https://img.shields.io/github/forks/CarbonPackages/Carbon.Eel.svg?style=social&label=Fork
[github stars]: https://img.shields.io/github/stars/CarbonPackages/Carbon.Eel.svg?style=social&label=Stars
[github watchers]: https://img.shields.io/github/watchers/CarbonPackages/Carbon.Eel.svg?style=social&label=Watch
[fork]: https://github.com/CarbonPackages/Carbon.Eel/fork
[stargazers]: https://github.com/CarbonPackages/Carbon.Eel/stargazers
[subscription]: https://github.com/CarbonPackages/Carbon.Eel/subscription
[licensefile]: LICENSE
[neos cms]: https://www.neos.io
[punkt.de]: https://github.com/punktde
