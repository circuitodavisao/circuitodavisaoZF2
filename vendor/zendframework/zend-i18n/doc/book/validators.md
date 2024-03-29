# Validators

zend-i18n provides a set of validators that use internationalization
capabilities.

## Alnum

`Zend\I18n\Validator\Alnum` allows you to validate if a given value contains
only alphabetical characters and digits. There is no length limitation for the
input you want to validate.

### Supported options

The following options are supported for `Zend\I18n\Validator\Alnum`:

- `allowWhiteSpace`: Whether or not whitespace characters are allowed. This
  option defaults to `FALSE`.

### Basic usage

```php
$validator = new Zend\I18n\Validator\Alnum();
if ($validator->isValid('Abcd12')) {
    // value contains only allowed chars
} else {
    // false
}
```

### Using whitespace

By default, whitespace is not accepted as it is not part of the alphabet.
However, if you want to validate complete sentences or phrases, you may need to
allow whitespace; this can be done via the `allowWhiteSpace` option, either at
instantiation or afterwards via the `setAllowWhiteSpace()` method.

To get the current state of the flag, use the `getAllowWhiteSpace()` method.

```php
$validator = new Zend\I18n\Validator\Alnum(['allowWhiteSpace' => true]);

// or set it via method call:
$validator->setAllowWhiteSpace(true);

if ($validator->isValid('Abcd and 12')) {
    // value contains only allowed chars
} else {
    // false
}
```

### Using different languages

Several languages supported by ext/intl use alphabets where characters are
formed from multiple bytes, including *Korean*, *Japanese*, and *Chinese*. Such
languages therefore are unsupported with regards to the `Alnum` validator.

When using the `Alnum` validator with these langauges, the input will be validated
using the English alphabet.

## Alpha

`Zend\I18n\Validator\Alpha` allows you to validate if a given value contains
only alphabetical characters. There is no length limitation for the input you
want to validate. This validator is identical to the `Zend\I18n\Validator\Alnum`
validator with the exception that it does not accept digits.

### Supported options

The following options are supported for `Zend\I18n\Validator\Alpha`:

- `allowWhiteSpace`: Whether or not whitespace characters are allowed. This
  option defaults to `FALSE`.

### Basic usage

```php
$validator = new Zend\I18n\Validator\Alpha();
if ($validator->isValid('Abcd')) {
    // value contains only allowed chars
} else {
    // false
}
```

### Using whitespace

By default, whitespace is not accepted as it is not part of the alphabet.
However, if you want to validate complete sentences or phrases, you may need to
allow whitespace; this can be done via the `allowWhiteSpace` option, either at
instantiation or afterwards via the `setAllowWhiteSpace()` method.

To get the current state of the flag, use the `getAllowWhiteSpace()` method.

```php
$validator = new Zend\I18n\Validator\Alpha(['allowWhiteSpace' => true]);

// or set it via method call:
$validator->setAllowWhiteSpace(true);

if ($validator->isValid('Abcd and efg')) {
    // value contains only allowed chars
} else {
    // false
}
```

### Using different languages

When using `Zend\I18n\Validator\Alpha`, the language provided by the user's
browser will be used to set the allowed characters. For locales outside of
English, this means that additional alphabetic characters may be used
&mdash; such as `ä`, `ö` and `ü` from the German alphabet.

Which characters are allowed depends completely on the language, as every
language defines its own set of characters.

Three languages supported by ext/intl, however, define multibyte characters,
which cannot be matched as alphabetic characters using normal string or regular
expression options. These include *Korean*, *Japanese*, and *Chinese*.

As a result, when using the `Alpha` validator with these langauges, the input
will be validated using the English alphabet.

## IsFloat

`Zend\I18n\Validator\IsFloat` allows you to validate if a given value contains a
floating-point value. This validator takes into account localized input.

### Supported options

The following options are supported for `Zend\I18n\Validator\IsFloat`:

- `locale`: Sets the locale to use when validating localized float values.

### Basic float validation

By default, if no locale is provided, `IsFloat` will use the system locale.

```php
$validator = new Zend\I18n\Validator\IsFloat();

$validator->isValid(1234.5);    // returns true
$validator->isValid('10a01');   // returns false
$validator->isValid('1,234.5'); // returns true
```

(The above example assumes that the environment locale is set to `en`.)

### Localized float validation

Float values are often written differently based on the country or region. For
example, using English, you might write `1.5`, whereas in german you would write
`1,5`, and in other languages you might use grouping.

`Zend\I18n\Validator\IsFloat` is able to validate such notations. However, it is
limited to the locale you set. See the following code:

```php
$validator = new Zend\I18n\Validator\IsFloat(['locale' => 'de']);

$validator->isValid(1234.5);    // returns true
$validator->isValid("1 234,5"); // returns false
$validator->isValid("1.234");   // returns true
```

By using a locale, your input is validated based on the locale provided. Using a
notation not specific to the locale results in a `false` evaulation.

The default validation locale can also be set after instantiation using
`setLocale()`, and retrieved using `getLocale()`.

### Migration from 2.0-2.3 to 2.4+

Version 2.4 adds support for PHP 7. In PHP 7, `float` is a reserved keyword,
which required renaming the `Float` validator. If you were using the `Float`
validator directly previously, you will now receive an `E_USER_DEPRECATED`
notice on instantiation. Please update your code to refer to the `IsFloat` class
instead.

Users pulling their `Float` validator instance from the validator plugin manager
receive an `IsFloat` instance instead starting in 2.4.0.

## IsInt

`Zend\I18n\Validator\IsInt` validates if a given value is an integer, using the
locale provided.

### Supported Options

The following options are supported for `Zend\I18n\Validator\IsInt`:

- `locale`: Sets the locale to use when validating localized integers.

### Basic integer validation

When no locale is provided to the validator, it uses the system locale:

```php
$validator = new Zend\I18n\Validator\IsInt();

$validator->isValid(1234);    // returns true
$validator->isValid(1234.5);  // returns false
$validator->isValid('1,234'); // returns true
```

(The above example assumes that the environment locale is set to `en`.)

### Localized integer validation

Integer values are often written differently based on country or region. For
example, using English, you may write "1234" or "1,234"; both are integer
values, but the grouping is optional. In German, you'd write "1.234", and in
French, "1 234".

`Zend\I18n\Validator\IsInt` will use a provided locale when evaluating the
validity of an integer value. In such cases, it doesn't simply strip the
validator, but instead validates that the correct separator as defined by the
locale is used.

```php
$validator = new Zend\I18n\Validator\IsInt(['locale' => 'de']);

$validator->isValid(1234);    // returns true
$validator->isValid("1,234"); // returns false
$validator->isValid("1.234"); // returns true
```

By using a locale, your input is validated based on the locale provided. Using a
notation not specific to the locale results in a `false` evaulation.

The default validation locale can also be set after instantiation using
`setLocale()`, and retrieved using `getLocale()`.

### Migration from 2.0-2.3 to 2.4+

Version 2.4 adds support for PHP 7. In PHP 7, `int` is a reserved keyword, which
required renaming the `Int` validator. If you were using the `Int` validator
directly previously, you will now receive an `E_USER_DEPRECATED` notice on
instantiation. Please update your code to refer to the `IsInt` class instead.

Users pulling their `Int` validator instance from the validator plugin manager
receive an `IsInt` instance instead starting in 2.4.0.
