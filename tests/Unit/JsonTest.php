<?php

declare(strict_types=1);

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use Beeyev\ValueObject\Json;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(Json::class)]
final class JsonTest extends AbstractTestCase
{
    #[DataProvider('provideValidStrings')]
    public function testAcceptsCorrectValue(string $value, mixed $expected): void
    {
        $valueObject = new Json($value);
        self::assertSame($value, $valueObject->value);
        self::assertSame($expected, $valueObject->toArray());
    }

    /**
     * @return array<mixed>
     */
    public static function provideValidStrings(): array
    {
        return [
            'Empty Object' => ['{}', []],
            'Simple Object' => ['{"name":"John", "age":30}', ['name' => 'John', 'age' => 30]],
            'Array of Objects' => ['[{"name":"John"}, {"name":"Jane"}]', [['name' => 'John'], ['name' => 'Jane']]],
            'Nested Structure' => ['{"person":{"name":"John", "age":30, "cars":["Ford", "BMW", "Fiat"]}}', ['person' => ['name' => 'John', 'age' => 30, 'cars' => ['Ford', 'BMW', 'Fiat']]]],
            'Basic Types' => ['{"string":"text", "number":10, "boolean":true, "null":null}', ['string' => 'text', 'number' => 10, 'boolean' => true, 'null' => null]],
            'Empty Array' => ['[]', []],
            'Array of Numbers' => ['[1, 2, 3]', [1, 2, 3]],
        ];
    }

    public function testThrowsExceptionIfInvalidJsonGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided string is not valid JSON. Given value: `{"name":"John"`');
        new Json('{"name":"John"');
    }

    public function testAcceptsNonEmptyStringableObject(): void
    {
        $valueObject = new Json(new StringableObject('{"name":"John", "age":30}'));
        self::assertSame('{"name":"John", "age":30}', $valueObject->value);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new Json('{"name":"John", "age":30}');
        self::assertSame('{"name":"John", "age":30}', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new Json('{"name":"John", "age":30}');
        self::assertSame('{"name":"John", "age":30}', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        new Json(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Json(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringableObjectGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Json(new StringableObject(''));
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Json('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Json::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Json::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new Json('{"name":"John", "age":30}');
        $valueObject2 = new Json('{"name":"John", "age":30}');
        $valueObject3 = new Json('{"name":"Monika", "age":33}');

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new Json('{"name":"John", "age":30}');
        $valueObject2 = new Json('{"name":"John", "age":30}');
        $valueObject3 = new Json('{"name":"Monika", "age":33}');

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
