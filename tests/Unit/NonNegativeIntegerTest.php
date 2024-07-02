<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\NonNegativeInteger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(NonNegativeInteger::class)]
final class NonNegativeIntegerTest extends AbstractTestCase
{
    #[DataProvider('integerDataProvider')]
    public function testAcceptsCorrectInputValue(int $value): void
    {
        $valueObject = new NonNegativeInteger($value);
        self::assertSame($value, $valueObject->value);
    }

    /**
     * @return array<mixed>
     */
    public static function integerDataProvider(): array
    {
        return [
            [0],
            [1],
            [100],
            [PHP_INT_MAX],
        ];
    }

    public function testDoesNotAcceptNegativeInputValue(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided number is not a non-negative integer. Given value: `-1`.');
        new NonNegativeInteger(-1);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new NonNegativeInteger(555);
        self::assertSame('555', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new NonNegativeInteger(555);
        self::assertSame('555', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type int/');
        new NonNegativeInteger(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new NonNegativeInteger(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type int/');
        new NonNegativeInteger(''); // @phpstan-ignore argument.type
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(NonNegativeInteger::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(NonNegativeInteger::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new NonNegativeInteger(555);
        $valueObject2 = new NonNegativeInteger(555);
        $valueObject3 = new NonNegativeInteger(111);

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new NonNegativeInteger(555);
        $valueObject2 = new NonNegativeInteger(555);
        $valueObject3 = new NonNegativeInteger(111);

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
