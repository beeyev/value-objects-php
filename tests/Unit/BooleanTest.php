<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Boolean;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(Boolean::class)]
final class BooleanTest extends AbstractTestCase
{
    public function testAcceptsBooleanValues(): void
    {
        $valueObject = new Boolean(true);
        self::assertTrue($valueObject->value);

        $valueObject = new Boolean(false);
        self::assertFalse($valueObject->value);
    }

    public function testAcceptsIntegerValues(): void
    {
        $valueObject = new Boolean(1);
        self::assertTrue($valueObject->value);

        $valueObject = new Boolean(0);
        self::assertFalse($valueObject->value);
    }

    #[DataProvider('stringBoolValuesProvider')]
    public function testAcceptsStringBoolValues(string $input, bool $expected): void
    {
        $valueObject = new Boolean($input);
        self::assertSame($expected, $valueObject->value);
    }

    /**
     * @return array<mixed>
     */
    public static function stringBoolValuesProvider(): array
    {
        return [
            ['1', true],
            ['true', true],
            ['TRUE', true],
            ['on', true],
            ['yes', true],
            ['0', false],
            ['false', false],
            ['FalsE', false],
            ['off', false],
            ['no', false],
        ];
    }

    public function testAcceptsNonEmptyStringableObject(): void
    {
        $valueObject = new Boolean(new StringableObject('true'));
        self::assertTrue($valueObject->value);
    }

    public function testReturnsStringRepresentationOfBoolean(): void
    {
        $valueObject = new Boolean(true);
        self::assertSame('true', (string) $valueObject);

        $valueObject = new Boolean(false);
        self::assertSame('false', (string) $valueObject);
    }

    public function testThrowsExceptionIfRandomStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Value could not be converted to boolean, given value: /');
        new Boolean('ololo');
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testPropertyIsAccessible(): void
    {
        $valueObject = new Boolean(true);
        self::assertTrue($valueObject->value);
    }

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new Boolean(true);
        self::assertSame('true', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new Boolean(true);
        self::assertSame('true', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string|int|bool/');
        new Boolean(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Boolean(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided string value cannot be empty.');
        new Boolean('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Boolean::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Boolean::class);

        self::assertTrue($reflection->getMethod('convertToBoolean')->isProtected());
        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new Boolean(true);
        $valueObject2 = new Boolean(true);
        $valueObject3 = new Boolean(false);

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new Boolean(true);
        $valueObject2 = new Boolean(true);
        $valueObject3 = new Boolean(false);

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
