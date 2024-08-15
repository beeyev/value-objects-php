# ValueObjects PHP Package 🫗

![Latest Version on Packagist](https://img.shields.io/packagist/v/beeyev/value-objects-php)
![Supported PHP Versions](https://img.shields.io/packagist/dependency-v/beeyev/value-objects-php/php.svg)

## ℹ️ Introduction

This package provides a collection of immutable value objects that you can use in your PHP applications.

Value objects are a key concept in Domain-Driven Design (DDD).  
They are simple objects whose equality is based on their value rather than their identity.  
Using value objects can help you write more expressive, reliable, and maintainable code.

## 🧾 Benefits of using value objects

- **Immutability**: Ensures objects remain consistent throughout their lifecycle.
- **Expressiveness**: Represents domain concepts naturally.
- **Validation**: Encapsulates validation logic, reducing errors.
- **Reusability**: Promotes DRY principles.
- **Ease of Testing**: Self-contained and simple to test.

## 📎 Why use value objects over primitives

- **Validation**: ❗ Guarantees valid data.
- **Self-Documenting Code**: Enhances readability.
- **Encapsulation**: Contains logic related to the value.
- **Consistency**: Ensures uniform handling of data.
- **Ease of Refactoring**: Centralizes changes to logic or validation rules.

## 📦 Installation

Use Composer to install this package. Run the following command:

```bash
composer require beeyev/value-objects-php
```

## ▶️ Usage

Here are examples of how to use the value objects provided by this package:

### Email

```php
use Beeyev\ValueObject\Email;

$email = new Email('abc@gmail.com');
echo $email->value;    // Output: 'abc@gmail.com'
echo $email->username; // Output: 'abc'
echo $email->domain;   // Output: 'gmail.com'
```

### URL

```php
use Beeyev\ValueObject\Url;

$url = new Url('https://example.com');
echo $url->value;   // Output: 'https://example.com'

// Every value object can be cast to a string
echo (string) $url; // Output: 'https://example.com'
```

### UUID

```php
use Beeyev\ValueObject\Uuid;

$uuid = new Uuid('550e8400-e29b-41d4-a716-446655440000');
echo $uuid->value; // Output: '550e8400-e29b-41d4-a716-446655440000'
```

### IPv4 Address

```php
use Beeyev\ValueObject\IPv4;

$ip = new IPv4('172.20.13.13');
echo $ip->value; // Output: '172.20.13.13'
```

### IPv6 Address

```php
use Beeyev\ValueObject\IPv6;

$ip = new IPv6('2606:4700:4700::1111');
echo $ip->value; // Output: '2606:4700:4700::1111'
```

### Coordinates

Represents a geographic coordinate (latitude and longitude).

```php
use Beeyev\ValueObject\Coordinate;

$coordinate = new Coordinate(37.7749, -122.4194);
echo $coordinate->latitude;  // Output: 37.7749
echo $coordinate->longitude; // Output: -122.4194
$coordinate->toArray();      // Array: [37.7749, -122.4194]

// Coordinate object can be created from a string
// Supported formats: '37.7749,-122.4194', '37.7749, -122.4194', '37.7749 122.4194', '37.7749/122.4194'
$coordinate = Coordinate::fromString('37.7749,-122.4194');

echo $coordinate->toString();  // Output: '37.7749, -122.4194'
// Or cast to a string
echo (string) $coordinate;     // Output: '37.7749, -122.4194'
```

### Json

Represents a JSON string.

```php
use Beeyev\ValueObject\Json;

$json = new Json('{"name": "John", "age": 30}');
echo $json->value;      // Output: '{"name": "John", "age": 30}'
echo $json->toArray();  // Output: ['name' => 'John', 'age' => 30]
```

### Percentage

Represents a percentage integer value from 0 to 100.

```php
use Beeyev\ValueObject\Percentage;

$percentage = new Percentage(50);
echo $percentage->value; // Output: 50
```

### RangeInteger

Represents a range of integer values.

```php
use Beeyev\ValueObject\RangeInteger;

$range = new RangeInteger(-5, 10);
echo $range->start;   // Output: -5
echo $range->end;     // Output: 10
$range->toArray();    // Array: [-5, 10]
echo (string) $range; // Output: '-5 - 10'


// Range object can be created from a string
$range = RangeInteger::fromString('-5 - 10');

// If you try to create a range object with the start value greater than the end value, an exception will be thrown
try {
    $range = new RangeInteger(10, -5);
} catch (ValueObjectInvalidArgumentException $e) {
    echo $e->getMessage(); // Output: 'Start value cannot be greater than the end value.'
}
```

### Resolution

Represents resolution (width and height).

```php
use Beeyev\ValueObject\Resolution;

// Only positive integers are allowed
$resolution = new Resolution(1920, 1080);
echo $resolution->width;   // Output: 1920
echo $resolution->height;  // Output: 1080
$resolution->toArray();    // Array: [1920, 1080]
echo (string) $resolution; // Output: '1920x1080'
```

### Semantic Version

Represents a semantic version number (SemVer).

```php
use Beeyev\ValueObject\SemVer;

$version = new SemVer('1.0.3');
echo $version->value; // Output: '1.0.3'
echo $version->major; // Output: 1
echo $version->minor; // Output: 0
echo $version->patch; // Output: 3

// Is supports semver with pre-release and build metadata
$version = new SemVer('1.0.3-beta+exp.sha.5114f85');
echo $version->value;          // Output: '1.0.3-beta+exp.sha.5114f85'
echo $version->releaseVersion; // Output: '1.0.3'
echo $version->build;          // Output: 'exp.sha.5114f85'
echo $version->preRelease;     // Output: 'beta'

// SemVer value objects can be compared
$version1 = new SemVer('1.0.5');
$version2 = new SemVer('1.0.1-alpha+001');

$version1->greaterThan($version2); // true
$version1->lowerThan($version2);   // false

$version1->equalTo($version2);     // false
$version1->notEqualTo($version2);  // true

$version1->greaterThanOrEqualTo($version2); // true
$version1->lowerThanOrEqualTo($version2);   // false
```

### Timestamp

Represents a unix timestamp.

```php
use Beeyev\ValueObject\Timestamp;

$timestamp = new Timestamp(1631535600);
echo $timestamp->value;   // Output: 1631535600
echo $timestamp->dateTime // Returns DateTimeImmutable object
```

### Class string

Represents a PHP class string.

```php
use Beeyev\ValueObject\ClassString;

$classString = new ClassString('App\Models\User');
// Same as
$classString = new ClassString(User::class);

echo $classString->value; // Output: 'App\Models\User'

// Returns true if the class exists
$classString->isClassExist(); // true

// Returns true if the object is an instance of this class string.
$classString->isInstanceOf($user); // true

// It is possible to instantiate an object from the class string
$classString = new ClassString(\DateTimeImmutable::class);
$instance = $classString->instantiate();
assert($instance instanceof \DateTimeImmutable);

// It is possible to instantiate an object from the class string with arguments
$classString = new ClassString(\DateTimeImmutable::class);
$instance = $classString->instantiateWith('2021-01-01 00:00:00', new \DateTimeZone('UTC'));
assert($instance instanceof \DateTimeImmutable);
echo $instance->format('Y-m-d H:i:s'); // Output: '2021-01-01 00:00:00'

// It is possible to check if the interface exists
$classString = new ClassString(\DateTimeInterface::class);
$classString->isInterfaceExist(); // true
```

## 🐒 Primitive Value Objects

### Text

Represents a non-empty text string.

```php
use Beeyev\ValueObject\Text;
use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

$text = new Text('Hello, World!');
echo $text->value;    // Output: 'Hello, World!'
echo (string) $text;  // Output: 'Hello, World!'
echo $text->length(); // Output: 13

// If you try to create an empty text object, an exception will be thrown
try {
    $text = new Text('');
} catch (ValueObjectInvalidArgumentException $e) {
    echo $e->getMessage(); // Output: 'Text value cannot be empty.'
}
```

### Boolean

```php
use Beeyev\ValueObject\Boolean;

$boolean = new Boolean(true);
// It is also possible to create a boolean object from non-boolean values
// Supported values: 'true', 'false', '1', '0', 'yes', 'no', 'on', 'off'
// $boolean = new Boolean('on');

echo $boolean->value;      // Output: true
echo $boolean->toString(); // Output: 'true'
echo (string) $boolean;    // Output: 'true'
```

### Integer

```php
use Beeyev\ValueObject\Integer;

$integer = new Integer(42);
// It is also possible to create an integer object from a string
// $integer = new Integer('42');

echo $integer->value; // Output: 42
```

### Positive Integer

Represents a positive integer greater than zero.
Useful for storing values that must always be positive.
For example, a database row ID.

```php
use Beeyev\ValueObject\PositiveInteger;
use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

$positiveInteger = new PositiveInteger(42);
echo $positiveInteger->value; // Output: 42

// If you try to create a positive integer object from a negative value or equal to zero, an exception will be thrown
try {
    $positiveInteger = new PositiveInteger(0);
} catch (ValueObjectInvalidArgumentException $e) {
    echo $e->getMessage(); // Output: 'Provided number is not a positive integer. Given value: `0`.'
}
```

### Non-Negative Integer

Represents a non-negative integer, greater than or equal to zero.

```php
use Beeyev\ValueObject\NonNegativeInteger;
use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

$positiveInteger = new NonNegativeInteger(96);
echo $positiveInteger->value; // Output: 96
```

### Double (float)

Represents a double-precision floating-point number.

```php
use Beeyev\ValueObject\Double;

$double = new Double(3.14);
// It is also possible to create a double object from a string
// $double = new Double('3.14');

echo $double->value;      // Output: 3.14
echo $double->toString(); // Output: '3.14'
echo (string) $double;    // Output: '3.14'
```

## Common functionality

Every value object has the following functionality:

```php
// Every value object can be cast to a string and supports \Stringable interface
$vo->toString(); // Returns the value of the object as a string
(string) $vo;    // Returns the value of the object as a string

// Value objects can be compared
$vo1->sameAs($vo2);    // Returns true if the values are equal
$vo1->notSameAs($vo2); // Returns true if the values are not equal
```

## 🏗 Creating your own value objects

It is possible to create your own value objects by extending the `AbstractValueObject` class.

## 📚 Extending functionality

Feel free to extend the functionality of the value objects by creating your own classes that inherit from the provided value objects.

## 🐛 Contributions

If you have suggestions for improvements or wish to create your own custom value object to be included as a built-in feature, please submit a Pull Request.  
Additionally, bug reports and feature requests can be submitted via the [GitHub Issue Tracker](https://github.com/beeyev/value-objects-php/issues).

## © License

The MIT License (MIT). Please see [License File](https://github.com/beeyev/value-objects-php/blob/master/LICENSE.md) for more information.

---

If you love this project, please consider giving me a ⭐

![](https://visitor-badge.laobi.icu/badge?page_id=beeyev.value-objects-php)
