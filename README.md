# Carbon.Eel Package for Neos CMS

## Available Helper

### `Carbon.String.convertCamelCase(string, seperator)`

Convert `CamelCaseStrings` to `hyphen-case-strings`

Examples:

```elm
Carbon.String.convertCamelCase('HelloWorld') == 'hello-world'
Carbon.String.convertCamelCase('HelloWorld', '_') == 'hello_world'
Carbon.String.convertCamelCase('HelloWorld', '') == 'helloworld'
```

*   `string` (string) The string to convert
*   `seperator` (string, optional) The seperator between the words, defaults to `-`

**Return** The converted string

### `Carbon.String.convertToString(input, seperator)`

Helper to make sure to get a string back

Examples:

```elm
Carbon.String.convertToString(' helloworld  ') == 'helloworld'
Carbon.String.convertToString([' hello', ' world']) == 'hello world'
Carbon.String.convertToString(['hello', 'world'], '-') == 'hello-world'
```

*   `input` (string, array) A string or an array to convert
*   `seperator` (string, optional) The seperator between the words, defaults to whitespace

**Return** The converted string

### `Carbon.String.nl2br(string, seperator)`

Replace all newlines with an `<br>`

Examples:

```elm
Carbon.String.nl2br('hello\nworld') == 'hello<br>world'
Carbon.String.nl2br('hello\nworld', ' | ') == 'hello | world'
```

*   `string` (string) A string to convert
*   `seperator` (string, optional) The seperator between the words, defaults to `<br>`

**Return** The converted string

### `Carbon.String.removeNbsp(string)`

Replace non-breaking spaces and double spaces with a normal space.

Examples:

```elm
Carbon.String.removeNbsp(' hello world') == 'hello world'
Carbon.String.removeNbsp('hello   world') == 'hello world'
```

*   `string` (string) A string to convert

**Return** The converted string

## Installation

Carbon.Eel is available via packagist. Just run

```bash
composer require carbon/eel
```
