[![Latest Stable Version](https://poser.pugx.org/carbon/eel/v/stable)](https://packagist.org/packages/carbon/eel)
[![Total Downloads](https://poser.pugx.org/carbon/eel/downloads)](https://packagist.org/packages/carbon/eel)
[![License](https://poser.pugx.org/carbon/eel/license)](LICENSE)
[![GitHub forks](https://img.shields.io/github/forks/CarbonPackages/Carbon.Eel.svg?style=social&label=Fork)](https://github.com/CarbonPackages/Carbon.Eel/fork)
[![GitHub stars](https://img.shields.io/github/stars/CarbonPackages/Carbon.Eel.svg?style=social&label=Stars)](https://github.com/CarbonPackages/Carbon.Eel/stargazers)
[![GitHub watchers](https://img.shields.io/github/watchers/CarbonPackages/Carbon.Eel.svg?style=social&label=Watch)](https://github.com/CarbonPackages/Carbon.Eel/subscription)

# Carbon.Eel Package for Neos CMS

## Array Helper

### `Carbon.Array.setKeyValue(array, key, value)`

Adds a key / value pair to an array

-   `array` (array) The array who should be extended
-   `key` (string) The name of the key
-   `value` (mixed) The value

### `Carbon.Array.ksort(array)`

Sort an array by key

-   `array` (array) The array who should be sorted

### `Carbon.Array.arrayFilter(array)`

Filters elements of an array. All entries of array equal to `false` will be removed.

-   `array` (array) The array who should be filtered

### `Carbon.Array.arrayValues(array)`

Return all the values of an array

-   `array` (array) The array who should be processed

### `Carbon.Array.joinRecursive(array, separator)`

Join the given array recursively using the given separator.

-   `array` (array) The array who should be processed
-   `separator` (string, optional) The separator between the values, defaults to `,`

### `Carbon.Array.extractSubElements(array, preserveKeys)`

This method extracts sub elements to the parent level.

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

-   `array` (array) The array who should be processed
-   `preserveKeys` (boolean, optional) Option if the key should be preserved, defaults to `false`

## FileContent Helper

### `Carbon.FileContent.path(string)`

Returns the file content of a path. Fails silent.

Examples:

```elm
Carbon.FileContent.path('resource://Vendor.Package/Private/Templates/InlineScript.js')
Carbon.FileContent.path('Vendor.Package/Private/Templates/InlineScript.js')
```

-   `string` (string) The path to read

### `Carbon.FileContent.pathHash(string, length)`

Returns the hash value from the file content of a path. Fails silent.

Examples:

```elm
Carbon.FileContent.pathHash('resource://Vendor.Package/Private/Templates/InlineScript.js') == 1d62f5a5
Carbon.FileContent.pathHash('Vendor.Package/Private/Templates/InlineScript.js', 20) == 1d62f5a55ad5e304d60d
```

-   `string` (string) The path to read
-   `length` (integer, optional) The length of the hash value, defaults to `8`. The maximal value is `40`

### `Carbon.FileContent.resource(resource)`

Returns the file content of a persisted resource. Fails silent.

Example:

```elm
Carbon.FileContent.resource(q(node).property('image'))
```

-   `resource` (resource) The persisted resource to read

### `Carbon.FileContent.resourceHash(resource, length)`

Returns the hash value from the file content of a persisted resource. Fails silent.

Example:

```elm
Carbon.FileContent.resourceHash(q(node).property('image')) == 1d62f5a5
Carbon.FileContent.resourceHash(q(node).property('image'), 20) == 1d62f5a55ad5e304d60d
```

-   `resource` (resource) The persisted resource to read
-   `length` (integer, optional) The length of the hash value, defaults to `8`. The maximal value is `40`

## String Helper

### `Carbon.String.urlize(string)`

Generates a slug of the given string

### `Carbon.String.convertCamelCase(string, separator)`

Convert `CamelCaseStrings` to `hyphen-case-strings`

Examples:

```elm
Carbon.String.convertCamelCase('HelloWorld') == 'hello-world'
Carbon.String.convertCamelCase('HelloWorld', '_') == 'hello_world'
Carbon.String.convertCamelCase('HelloWorld', '') == 'helloworld'
```

-   `string` (string) The string to convert
-   `separator` (string, optional) The separator between the words, defaults to `-`

**Return** The converted string

### `Carbon.String.convertToString(input, separator)`

Helper to make sure to get a string back

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

Replace all newlines with an `<br>`

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

## Installation

Carbon.Eel is available via packagist. Just run

```bash
composer require carbon/eel
```
