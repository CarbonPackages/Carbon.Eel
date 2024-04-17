[![Latest stable version]][packagist] [![Total downloads]][packagist] [![License]][licensefile] [![GitHub forks]][fork] [![GitHub stars]][stargazers] [![GitHub watchers]][subscription]

# Carbon.Eel Package for [Neos CMS]

## BEM Helper

Generates BEM classes. The modifiers property can be a string (for one modifier), an array (e.g. `['one', 'two']`), or an array with keys and values. If you have an array with keys and values (like a Fusion DataStructure) and a value is `true`, the key's name gets used for the modifier.

- `block` (string, required) The name of the block
- `element` (string) The name of the element, optional
- `modifiers` (string|array) The name of the modifiers, optional

### `BEM.array(block, element, modifiers)`

Shortcut to `Carbon.Array.BEM(block, element, modifiers)`

### `BEM.string(block, element, modifiers)`

Shortcut to `Carbon.String.BEM(block, element, modifiers)`

### `BEM.modifier(class, modifiers)`

Generates a string with BEM classes. The modifiers property can be a string (for one modifier), an array (e.g. `['one', 'two']`), or an array with keys and values. If you have an array with keys and values (like a Fusion DataStructure) and the value is `true`, the key's name gets used for the modifier.

- `class` (string, required) The name of the class
- `modifiers` (string|array) The name of the modifiers, optional

**Return** The string

## Array Helper

### `Carbon.Array.BEM(block, element, modifiers)`

Generates an array with BEM classes. The modifiers property can be a string (for one modifier), an array (e.g. `['one', 'two']`), or an array with keys and values. If you have an array with keys and values (like a Fusion DataStructure) and the value is `true`, the key's name gets used for the modifier.

- `block` (string, required) The name of the block
- `element` (string) The name of the element, optional
- `modifiers` (string|array) The name of the modifiers, optional

**Return** The array

### `Carbon.Array.chunck(array, length, preserveKeys)`

Chunks an array into arrays with `length` elements. The last chunk may contain less than `length` elements.

- `array` (array, required) The array to work on
- `length` (integer, required) The size of each chunk
- `preserveKeys` (bool) When set to `true`, keys will be preserved. Default is `false`, which will reindex the chunk numerically

### `Carbon.Array.join(array, separator)`

Join the given array recursively using the given separator.

```elm
${Carbon.Array.join(array, ',')}
```

- `array` (array) The array that should be processed
- `separator` (string, optional) The separator between the values defaults to `,`

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

- `array` (array) The array that should be processed
- `preserveKeys` (boolean, optional) Option if the key should be preserved, defaults to `false`

**Return** The converted array

### `Carbon.Array.intersect(array1, array2, array_)`

- `array1` (iterable|mixed) First array or value
- `array2` (iterable|mixed) Second array or value
- `array_` (iterable|mixed, optional) Optional variable list of additional arrays / values

Returns an array containing all the values of `array1` that are present in all the arguments.

### `Carbon.Array.length(array)`

The method counts elements of a given array or a countable object. Return `0` if it is not an countable object.

```elm
count = ${Carbon.Array.length(countable)}
```

### `Carbon.Array.hasKey(array, key)`

Returns a boolean if the array has a specific key

```elm
bool = ${Carbon.Array.hasKey(array, key)}
```

### `Carbon.Array.hasValue(array, value)`

Returns a boolean if the array has a specific value

```elm
bool = ${Carbon.Array.hasValue(array, value)}
```

### `Carbon.Array.getValueByPath(array, path)`

Returns the value of a nested array by following the specified path.

```elm
value = ${Carbon.Array.getValueByPath(array, path)}
```

### `Carbon.Array.setValueByPath(array, path, value)`

Sets the given value in a nested array or object by following the specified path.

```elm
array = ${Carbon.Array.setValueByPath(subject, path, value)}
```

### `Carbon.Array.check(variable)`

Check if a variable is iterable and has items

**Return** The variable or `null` if it is empty or not an iterable

### `Carbon.Array.isCountable(variable)`

Check if the given variable is countable

**Return** `true` or `false`

### `Carbon.Array.sortByItem(array, key, direction)`

- `array` (iterable|mixed) array with arrays to sort
- `key` (string) Key of array to sort
- `direction` (string, optional) `ASC` or `DESC`. Direction of sorting

**Return** The sorted array

## Date Helper

### `Carbon.Date.secondsUntil(string)`

Return seconds until the given offset. . Very useful for `maximumLifetime` on the `@cache` entry.

- `string` (string) The offset in [`DateInterval` format] starting from midnight
- `dateinerval` (boolean, optional) true if interval should be used or the $offset should be parsed, defaults to `true`

In this example, we clear the cache at midnight by adding an offset of 0 hours.

```elm
@cache {
    mode = 'cached'
    maximumLifetime = ${Carbon.Date.secondsUntil('PT0H')}
    ...
}
```

To get the seconds until next year, you can do it like this:

```elm
secondUntilNextYear = ${Carbon.Date.secondsUntil('first day of January next year', false)}
```

**Return** The timespan in seconds (integer)

### `Carbon.Date.timeToDateInterval(string)`

Convert time duration (1:00) into a [`DateInterval`]

**Return** The duration as DateInterval

## FileContent Helper

### `Carbon.FileContent.path(string)`

Returns the file content of a path. Fails silently.

Examples:

```elm
Carbon.FileContent.path('resource://Foo.Bar/Private/Assets/Logo.svg')
Carbon.FileContent.path('Foo.Bar/Private/Assets/Logo.svg')
```

- `string` (string) The path to the file

**Return** The content of the file

### `Carbon.FileContent.pathHash(string, length)`

Returns the hash value from the file content of a path. Fails silently.

Examples:

```elm
Carbon.FileContent.pathHash('resource://Foo.Bar/Private/Assets/Logo.svg') == 1d62f5a5
Carbon.FileContent.pathHash('Foo.Bar/Private/Assets/Logo.svg', 20) == 1d62f5a55ad5e304d60d
```

- `string` (string) The path to the file
- `length` (integer, optional) The length of the hash value defaults to `8`. The maximal value is `40`

**Return** The hash value from the content of the file

### `Carbon.FileContent.resource(resource)`

Returns the file content of a persisted resource. Fails silently.

Example:

```elm
Carbon.FileContent.resource(q(node).property('file'))
```

- `resource` (resource) The persisted resource to read

**Return** The content of the file

### `Carbon.FileContent.resourceHash(resource, length)`

Returns the hash value from the file content of a persisted resource. Fails silently.

Example:

| Expression                                                       | Result                   |
| ---------------------------------------------------------------- | ------------------------ |
| `Carbon.FileContent.resourceHash(q(node).property('file'))`      | `'1d62f5a5'`             |
| `Carbon.FileContent.resourceHash(q(node).property('file'), 20)`  | `'1d62f5a55ad5e304d60d'` |

- `resource` (resource) The persisted resource to read
- `length` (integer, optional) The length of the hash value defaults to `8`. The maximal value is `40`

**Return** The hash value from the content of the file

## Tailwind Helper

### `Tailwind.merge(mixed1, mixed2, mixedN)`

This helper allows you to merge multiple [Tailwind CSS] classes and automatically resolves conflicts between them without headaches.
Render all arguments as classNames and apply conditions if needed.
Merge strings and arrays to a string with unique values, separated by an empty space.

All arguments of the eel helper are evaluated and the following rules are applied:

- falsy values: (`null`, `''`, `[]`, `{}`) are not rendererd
- array: all items that are scalar and truthy are rendered as classname
- object: keys that have a truthy values are rendered as classname
- scalar: is cast to string and rendered as class-name

It is based on [tailwind-merge-php].

Examples:

| Expression                                                          | Result                 |
| ------------------------------------------------------------------- | ---------------------- |
| `Tailwind.merge('w-8 h-8 rounded-full rounded-lg')`                 | `'w-8 h-8 rounded-lg'` |
| `Tailwind.merge(['w-8 rounded-full'], 'rounded-lg', 'h-8')`         | `'w-8 rounded-lg h-8'` |
| `Tailwind.merge(null, null, {}, [])`                                | `null`                 |
| `Tailwind.merge('one', ['one', 'two'], {three: true, four: false}`  | `'one two three'`      |

**Return** The merged string

#### Configuration

If you are using Tailwind CSS without any extra config, you can use the Eel helper right away. And stop reading here.

If you're using a custom Tailwind config, you may need to configure the Eel helper as well to merge classes properly.

By default it is configured in a way that you can still use it if all the following apply to your Tailwind config:

- Only using color names which don't clash with other Tailwind class names
- Only deviating by number values from number-based Tailwind classes
- Only using font-family classes which don't clash with default font-weight classes
- Sticking to default Tailwind config for everything else

If some of these points don't apply to you, you need to customize the configuration.

This is an example to add a custom font size of `very-large`:

```yaml
Carbon:
  Eel:
    tailwindMergeConfig:
      classGroups:
        'font-size':
          - text: ['very-large']
```

You can also enable different [validators], to make everything easier. For instance, if you use the
[Tailwind OKLCH Plugin], you can set it like that:

```yaml
Carbon:
  Eel:
    tailwindMergeConfig:
      classGroups:
        'fill-lightness':
          - 'fill-lightness-offset': ['INTEGER_VALIDATOR', 'ARBITRARY_NUMBER_VALIDATOR']
        'border-lightness':
          - 'border-lightness-offset': ['INTEGER_VALIDATOR', 'ARBITRARY_NUMBER_VALIDATOR']
        'text-lightness':
          - 'text-lightness-offset': ['INTEGER_VALIDATOR', 'ARBITRARY_NUMBER_VALIDATOR']
        'bg-lightness':
          - 'bg-lightness-offset': ['INTEGER_VALIDATOR', 'ARBITRARY_NUMBER_VALIDATOR']
```

If you want to use a certain validator, just change it to constant case and add it as a string.
So, for examle, if you want to use the `TshirtSizeValidator`, just add `TSHIRT_SIZE_VALIDATOR` to the list.

> The merge service uses a its own cache `Carbon_Eel_Tailwind`. Make sure to clear the cache when you are making changes to the configuration.

## AlpineJS Helper

### `AlpineJS.object(arg1, arg2, ..argN)`

Generate an object for AlpineJS directive [`x-data`](https://alpinejs.dev/directives/data).
Supports nested arrays. You could do the same with `Json.stringify()`, but this function is shorter, as AlpineJS
accepts objects and easier to write and read.

Examples:

| Expression                                                                            | Result                                                         |
| ------------------------------------------------------------------------------------- | -------------------------------------------------------------- |
| `AlpineJS.object({effect: 'slide', spaceBetween: 12, loop: true, navigation: null})`  | `'{effect:'slide',spaceBetween:12,loop:true,navigation:null}'` |
| `AlpineJS.object({nested: {value: true, nulled: null}})`                              | `'{nested:{value:true,nulled:null}}'`                          |

- `...arguments` The array

**Return** The JavaScript object as string

### `AlpineJS.function(name, arg1, arg2, ..argN)`

Generate a function call for AlpineJS. [More info](https://alpinejs.dev/globals/alpine-data).
Supports nested arrays. In named arrays (`{first:1,second:null}`) `null` get filtered out, but in list
arrays (`[1,null]`) and in plain values the will stay.

Examples:

| Expression                                                                                        | Result                                                 |
| ------------------------------------------------------------------------------------------------- | ------------------------------------------------------ |
| `AlpineJS.function('slider', {effect: 'slide', spaceBetween: 12, loop: true, navigation: null})`  | `'slider({effect:'slide',spaceBetween:12,loop:true})'` |
| `AlpineJS.function('slider', 'one', 1, false, null, ['string', 2, null])`                         | `'slider('one',1,false,null,['string',2,null])'`       |
| `AlpineJS.function("vote", 4)`                                                                    | `'vote(4)'`                                            |

- `name` (string) The name for the function (e.g `x-data`, `x-on:click`, etc.)
- `...arguments` (mixed) The options for the function

**Return** The string for the x-data function call

### `AlpineJS.magic(name, arg1, arg2, ..argN)`

Generate a call for a magic function for AlpineJS..
Supports nested arrays. In named arrays (`{first:1,second:null}`) `null` get filtered out, but in list
arrays (`[1,null]`) and in plain values the will stay.

Examples:

| Expression                                                                        | Result                                            |
| --------------------------------------------------------------------------------- | ------------------------------------------------- |
| `AlpineJS.magic('dispatch', 'notify')`                                            | `'$dispatch('notify')'`                           |
| `AlpineJS.magic('$dispatch', 'notify', { message: 'Hello World!' })`              | `'$dispatch('notify',{message:'Hello World!'})'`  |
| `AlpineJS.magic('dispatch', 'notify', { message: true, nested: {value: true} })`  | `'$dispatch('notify',{message:'Hello World!'})'`  |

- `name` (string) The name for the magic. If not prefixed with an `$`, it will automatically prefixed.
- `...arguments` (mixed) The options for the function

**Return** The string for the magic function call

### `AlpineJS.expression(value)`

Use this to pass a javascript expression inside of the `AlpineJS.object`, `AlpineJS.function` or `AlpineJS.magic` helper

Examples:

| Expression                                                                                          | Result                                                             |
| --------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------ |
| `AlpineJS.object({theme: AlpineJS.expression("localStorage.theme\|\|'system'"), show: true})`       | `'{theme:localStorage.theme\|\|'system',show:true}'`                |
| `AlpineJS.function('themeSwitcher', {theme: AlpineJS.expression("localStorage.theme\|\|'system'")`  | `'themeSwitcher({theme:localStorage.theme\|\|'system',show:true})'` |

## String Helper

### `Carbon.String.BEM(block, element, modifiers)`

Generates a string with BEM classes. The modifiers property can be a string (for one modifier), an array (e.g. `['one', 'two']`), or an array with keys and values. If you have an array with keys and values (like a Fusion DataStructure) and the value is `true`, the key's name gets used for the modifier.

- `block` (string, required) The name of the block
- `element` (string) The name of the element, optional
- `modifiers` (string|array) The name of the modifiers, optional

**Return** The string

### `Carbon.String.urlize(string)`

Generates a slug of the given string

Examples:

| Expression                             | Result              |
| -------------------------------------- | ------------------- |
| `Carbon.String.urlize('Hello World')`  | `'hello-world'`     |
| `Carbon.String.urlize('Ä_ÖÜ äöü')`     | `'ae-oeue-aeoeue'`  |

- `string` (string) The string to convert

**Return** The converted string

### `Carbon.String.minifyJS(javascript)`

Minifies JavaScript so that it can be delivered to the client quicker.

- `javascript` (string, required) The JavaScript to minify

**Return** The minified JavaScript

### `Carbon.String.minifyCSS(javascript)`

Minifies CSS so that it can be delivered to the client quicker.

- `css` (string, required) The CSS to minify

**Return** The minified CSS

### `Carbon.String.toPascalCase(string)`

Convert strings to `PascalCase`.

Examples:

| Expression                                   | Result          |
| -------------------------------------------- | --------------- |
| `Carbon.String.toPascalCase('hello-world')`  | `'HelloWorld'`  |
| `Carbon.String.toPascalCase('hello world')`  | `'HelloWorld'`  |
| `Carbon.String.toPascalCase('Hello World')`  | `'HelloWorld'`  |

- `string` (string) The string to convert

**Return** The converted string

### `Carbon.String.toCamelCase(string)`

Convert strings to `camelCase`.

Examples:

| Expression                                  | Result          |
| ------------------------------------------- | --------------- |
| `Carbon.String.toCamelCase('hello-world')`  | `'helloWorld'`  |
| `Carbon.String.toCamelCase('hello world')`  | `'helloWorld'`  |
| `Carbon.String.toCamelCase('Hello World')`  | `'helloWorld'`  |

- `string` (string) The string to convert

**Return** The converted string

### `Carbon.String.convertCamelCase(string, separator)`

Convert `CamelCaseStrings` to `hyphen-case-strings`

Examples:

| Expression                                           | Result           |
| ---------------------------------------------------- | ---------------- |
| `Carbon.String.convertCamelCase('HelloWorld')`       | `'hello-world'`  |
| `Carbon.String.convertCamelCase('HelloWorld', '_')`  | `'hello_world'`  |
| `Carbon.String.convertCamelCase('HelloWorld', '')`   | `'helloworld'`   |

- `string` (string) The string to convert
- `separator` (string, optional) The separator between the words defaults to `-`

**Return** The converted string

### `Carbon.String.convertToString(input, separator)`

Helper to make sure to get a string back.

Examples:

| Expression                                                   | Result           |
| ------------------------------------------------------------ | ---------------- |
| `Carbon.String.convertCamelCase(' helloworld  ')`            | `'helloworld'`   |
| `Carbon.String.convertCamelCase([' hello', ' world'])`       | `'hello world'`  |
| `Carbon.String.convertCamelCase([' hello', ' world'], '-')`  | `'hello-world'`  |

- `input` (string, array) A string or an array to convert
- `separator` (string, optional) The separator between the words, defaults to whitespace

**Return** The converted string

### `Carbon.String.nl2br(string, separator)`

Replace all newlines with an `<br>`.

Examples:

| Expression                                    | Result              |
| --------------------------------------------- | ------------------- |
| `Carbon.String.nl2br('hello\nworld')`         | `'hello<br>world'`  |
| `Carbon.String.nl2br('hello\nworld', ' - ')`  | `'hello - world'`   |

- `string` (string) A string to convert
- `separator` (string, optional) The separator between the words, defaults to `<br>`

**Return** The converted string

### `Carbon.String.removeNbsp(string)`

Replace non-breaking spaces and double spaces with a normal space.

Examples:

| Expression                                   | Result           |
| -------------------------------------------- | ---------------- |
| `Carbon.String.removeNbsp(' hello world')`   | `'hello world'`  |
| `Carbon.String.removeNbsp('hello   world')`  | `'hello world'`  |

- `string` (string) A string to convert

**Return** The converted string

### `Carbon.String.classNames(mixed1, mixed2, mixedN)`

Render all arguments as classNames and apply conditions if needed.
Merge strings and arrays to a string with unique values, separated by an empty space.

All arguments of the eel helper are evaluated and the following rules are applied:

- falsy values: (`null`, `''`, `[]`, `{}`) are not rendererd
- array: all items that are scalar and truthy are rendered as classname
- object: keys that have a truthy values are rendered as classname
- scalar: is cast to string and rendered as class-name

Examples:

| Expression                                                     | Result                 |
| -------------------------------------------------------------- | ---------------------- |
| `Carbon.String.classNames('', 'one')`                          | `'one'`                |
| `Carbon.String.classNames(['one two three'], ['one', 'four'])` | `'one two three four'` |
| `Carbon.String.classNames(null, false, [null], {one: false})`  | `null`                 |
| `Carbon.String.classNames('one two three', ['one', 'four'])`   | `'one two three four'` |
| `Carbon.String.classNames('one', {two: true, three: false })`  | `'one two'`            |

**Return** The merged string

### `Carbon.String.splitIntegerAndString(string)`

Split a string into an array width integers and strings. Useful for animations.

Examples:

| Expression                                                 | Result                          |
| ---------------------------------------------------------- | ------------------------------- |
| `Carbon.String.splitIntegerAndString('1000+ customers')`   | `[1000, '+ customers']`         |
| `Carbon.String.splitIntegerAndString('24/7 reachability')` | `[24, '/', 7, ' reachability']` |
| `Carbon.String.splitIntegerAndString('0 issues')`          | `[0, ' issues']`                |
| `Carbon.String.splitIntegerAndString('')`                  | `[]`                            |
| `Carbon.String.splitIntegerAndString(null)`                | `[]`                            |
| `Carbon.String.splitIntegerAndString(100)`                 | `[100]`                         |

- `string` (string) The string to split

**Return** The string, splitted into an array of integers and strings

### `Carbon.String.phone(phoneNumber, defaultCountryCode, prefix)`

Helper to convert phone numbers to a compatible format for links

Examples:

| Expression                                         | Result                |
| -------------------------------------------------- | --------------------- |
| `Carbon.String.phone(' 079 123 45 67 ')`           | `'tel:0791234567'`    |
| `Carbon.String.phone('+41 (0) 79 123/45/67')`      | `'tel:0041791234567'` |
| `Carbon.String.phone('079 123 45 67', '+41')`      | `'tel:0041791234567'` |
| `Carbon.String.phone('079 123 45 67', null, null)` | `'0791234567'`        |
| `Carbon.String.phone(' / (0) ')`                   | `null`                |

- `phoneNumber` (string) The phone number to convert
- `defaultCountryCode` (string, optional) The default country code, for example `'+41'`
- `prefix` (string, optional) Prefix the phone number, defaults to `'tel:'`

**Return** The phone number, optimized for links

### `Carbon.String.isValidEmail(emailAddress)`

Checks if the string is a valid email address

Examples:

| Expression                                        | Result  |
| ------------------------------------------------- | ------- |
| `Carbon.String.isValidEmail('')`                  | `false` |
| `Carbon.String.isValidEmail('Some text')`         | `false` |
| `Carbon.String.isValidEmail('hello@uhlmann.pro')` | `true`  |

- `emailAddress` (string) The string to check

### `Carbon.String.replaceOnce(string, search, replace)`

Helper to replace the first occurrence of a string.

Examples:

| Expression                                             | Result        |
| ------------------------------------------------------ | ------------- |
| `Carbon.String.replaceOnce('Foo Bar Foo', 'Foo', 'X')` | `'X Bar Foo'` |
| `Carbon.String.replaceOnce('Foo Bar Foo', 'Foo ')`     | `'Bar Foo'`   |

- `string` (string) The string being searched and replaced on
- `search` (string) The value being searched for
- `prefix` (string, optional) The replacement value that replaces found search value

Returns the string with one occurrence replaced

## Number Helper

### `Carbon.Number.format(number, decimals, dec_point, thousands_sep)`

Format a number with grouped thousands. If `decimals` is set to `null`, it returns as many as needed decimals.

- `number` (float, required) The number being formatted
- `decimals` (integer, optional) Sets the number of decimal points, defaults to `null`
- `dec_point` (string, optional) The name of the modifier defaults to `.`
- `thousands_sep` (string, optional) The name of the modifier defaults to `,`

### `Carbon.Number.formatLocale(number, decimals, locale)`

Format a localized number with grouped thousands. If `decimals` is set to `null`, it returns as many as needed decimals.

- `number` (float, required) The number being formatted
- `decimals` (integer, optional) Sets the number of decimal points, defaults to `null`
- `locale` (string, optional) String locale - example (de_DE|en|ru_RU)

### `Carbon.Number.decimalDigits(number)`

Get number of decimal digits.

- `number` (float, required) The number being formatted

### `Carbon.Number.pxToRem(value, fallback)`

Convert a pixel value to rem

- `value` (numeric | string, required) The value to converted
- `fallback` (numeric | string, optional) Fallback value if `value` is `false` or `null`

Examples:

| Expression                            | Result     |
| ------------------------------------- | ---------- |
| `Carbon.Number.pxToRem('')`           | `'0'`      |
| `Carbon.Number.pxToRem('8px')`        | `'0.5rem'` |
| `Carbon.Number.pxToRem(16)`           | `'1rem'`   |
| `Carbon.Number.pxToRem(0)`            | `'0'`      |
| `Carbon.Number.pxToRem(false, 32)`    | `'2rem'`   |
| `Carbon.Number.pxToRem(null, '16px')` | `'1rem'`   |

Returns a string with the converted value

## Backend Helper

### `Carbon.Backend.language()`

Returns the language from the interface

### `Carbon.Backend.translate(id, originalLabel, arguments, source, package, quantity, locale)`

Get the translated value (in the language of the interface) for an id or original label. If the only id is set and contains a translation shorthand string, translate according to that shorthand.

In all other cases:
Replace all placeholders with corresponding values if they exist in the translated label.

- `id` (string) Id to use for finding translation (trans-unit id in XLIFF)
- `originalLabel` (string, optional) The original translation value (the untranslated source string)
- `arguments` (array, optional) Array of numerically indexed or named values to be inserted into placeholders
- `source` (string, optional) Name of file with translations
- `package` (string, optional) Target package key
- `quantity` (mixed, optional) A number to find a plural form for (float or int), `null` to not use plural forms
- `locale` (string, optional) An identifier of the locale to use (NULL for use the interface language)

Returns the translated label or source label / ID key

## Installation

Carbon.Eel is available via packagist. Just run

```bash
composer require carbon/eel
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
[`dateinterval` format]: https://www.php.net/manual/en/dateinterval.format.php
[`dateinterval`]: https://www.php.net/manual/de/class.dateinterval.php
[Tailwind CSS]: https://tailwindcss.com
[tailwind-merge-php]: https://github.com/gehrisandro/tailwind-merge-php
[validators]: https://github.com/gehrisandro/tailwind-merge-php/tree/main/src/Validators
[tailwind oklch plugin]: https://github.com/MartijnCuppens/tailwindcss-oklch
