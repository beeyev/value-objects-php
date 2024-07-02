<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use Beeyev\ValueObject\Text;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(Text::class)]
final class TextTest extends AbstractTestCase
{
    #[DataProvider('provideValidStrings')]
    public function testAcceptsString(string $value): void
    {
        $valueObject = new Text($value);
        self::assertSame($value, $valueObject->value);
    }

    /**
     * @return array<mixed>
     */
    public static function provideValidStrings(): array
    {
        return [
            ['hello'],
            ['world123'],
            ['testCase'],
            ['hello_world!'],
            ['welcome@2024'],
            ['pass#word$'],
            ['  leading'],
            ['trailing  '],
            ['  both  '],
            ['こんにちは'],
            ['你好'],
            ['😊👍'],
            ['français'],
            ['123456'],
            ['000001'],
            ['9876543210'],
            ['test@example.com'],
            ['user.name@domain.co'],
            ['email@sub.domain.org'],
            ['https://www.example.com'],
            ['http://example.org/test'],
            ['ftp://files.example.net'],
            ['C:\\\Program Files\\\App'],
            ['/usr/local/bin'],
            ['./relative/path'],
            ['CamelCaseString'],
            ['snake_case_string'],
            ['UPPERCASE'],
            ["line1\nline2"],
            ["tab\tseparated"],
            ['quote"inside'],
            ['a'],
            ['short'],
            ['This is a bit longer string.'],
            ['This is a very long string to test the handling of larger inputs in the system. It includes many characters to ensure that the handling is appropriate and no errors occur during processing.'],
            ['{"key":"value"}'],
            ['[{"id":1,"name":"test"},{"id":2,"name":"example"}]'],
            ['<div>Hello World</div>'],
            ['<p>This is a paragraph.</p>'],
            ['SELECT * FROM users WHERE id = 1;'],
            ["' OR '1'='1"],
            ['1A2B3C4D'],
            ['abcdef123456'],
        ];
    }

    #[DataProvider('provideTypeCastedValidStrings')]
    public function testTypeCastedValidStrings(string $value, string $expected): void
    {
        $valueObject = new Text($value);
        self::assertSame($expected, $valueObject->value);
    }

    /**
     * @return array<mixed>
     */
    public static function provideTypeCastedValidStrings(): array
    {
        return [
            [(string) true, '1'],
            [(string) 0, '0'],
            [(string) 1, '1'],
            [(string) 123, '123'],
            [(string) 0.0, '0'],
            [(string) 1.0, '1'],
            [(string) 123.456, '123.456'],
            [(string) -1, '-1'],
            [(string) -123, '-123'],
            [(string) -123.456, '-123.456'],
        ];
    }

    public function testAcceptsNonEmptyStringableObject(): void
    {
        $valueObject = new Text(new StringableObject('abc2'));
        self::assertSame('abc2', $valueObject->value);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new Text('abc');
        self::assertSame('abc', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new Text('abc');
        self::assertSame('abc', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        new Text(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Text(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringableObjectGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Text(new StringableObject(''));
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Text('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Text::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Text::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new Text('test');
        $valueObject2 = new Text('test');
        $valueObject3 = new Text('different');

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new Text('test');
        $valueObject2 = new Text('test');
        $valueObject3 = new Text('different');

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }

    public function testLength(): void
    {
        $valueObject = new Text('test-@123абвენა👍✨');
        self::assertSame(17, $valueObject->length());
    }
}
