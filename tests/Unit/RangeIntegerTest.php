<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Support\IntegerStringValidator;
use Beeyev\ValueObject\Support\TypeCast;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\RangeInteger;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(RangeInteger::class)]
#[UsesClass(IntegerStringValidator::class)]
#[UsesClass(TypeCast::class)]
final class RangeIntegerTest extends AbstractTestCase
{
    #[DataProvider('validRangeIntegersProvider')]
    public function testValidRangeIntegers(int $start, int $end): void
    {
        $rangeInteger = new RangeInteger($start, $end);
        self::assertEquals($start, $rangeInteger->start);
        self::assertEquals($end, $rangeInteger->end);
        self::assertSame([$start, $end], $rangeInteger->toArray());
    }

    /**
     * @return array<mixed>
     */
    public static function validRangeIntegersProvider(): array
    {
        return [
            [0, 10], // range from 0 to 10
            [-10, 0], // range from -10 to 0
            [1, 100], // range from 1 to 100
            [-100, -1], // range from -100 to -1
            [PHP_INT_MIN, PHP_INT_MAX], // range from minimum to maximum integer
        ];
    }

    public function testAcceptsCorrectRangeIntegerString(): void
    {
        $valueObject = new RangeInteger('-10', '541');
        self::assertSame([-10, 541], $valueObject->toArray());
    }

    public function testThrowsExceptionIfStartValueIsLowerTnanEndValue(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Range Start value cannot be greater than End value. Given values: `10` and `0`');
        new RangeInteger(10, 0);
    }

    public function testThrowsExceptionIfIntegerValueIsTooBig(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided End value is too big and cannot be represented as integer. Given value: `39223372036854775808`');
        new RangeInteger(10, '39223372036854775808');
    }

    public function testThrowsExceptionIfIntegerValueIsTooSmall(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided Start value is too small and cannot be represented as integer. Given value: `-39223372036854775808`');
        new RangeInteger('-39223372036854775808', 10);
    }

    public function testThrowsExceptionIfStartValueIsNotNumeric(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided Start value is not a valid integer. Given value: `abc`');
        new RangeInteger('abc', 10);
    }

    public function testThrowsExceptionIfEndValueIsNotNumeric(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided End value is not a valid integer. Given value: `abc`');
        new RangeInteger(10, 'abc');
    }

    public function testAcceptsCorrectRangeIntegerStringable(): void
    {
        $valueObject = new RangeInteger(new StringableObject('123'), new StringableObject('541'));
        self::assertSame([123, 541], $valueObject->toArray());
    }

    #[DataProvider('createObjectFromStringDataProvider')]
    public function testSuccessfullyCreateObjectFromString(string $rangeIntegers): void
    {
        $valueObject = RangeInteger::fromString($rangeIntegers);
        self::assertSame([-574, 1278], $valueObject->toArray());
    }

    /**
     * @return array<mixed>
     */
    public static function createObjectFromStringDataProvider(): array
    {
        return [
            ['-574,1278'],
            ['-574, 1278'],
            ['-574 1278'],
            ['-574/1278'],
            ['-574..1278'],
        ];
    }

    #[DataProvider('createInvalidObjectFromStringDataProvider')]
    public function testFailedCreateObjectFromString(string $rangeIntegers): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Range string can not be parsed. Given value:/');
        RangeInteger::fromString($rangeIntegers);
    }

    /**
     * @return array<mixed>
     */
    public static function createInvalidObjectFromStringDataProvider(): array
    {
        return [
            ['-574-572'],
            ['-574 , 1278'],
            ['-515/074 1278'],
            ['-515074 / 1278'],
            ['-5174'],
        ];
    }

    public function testSuccessfullyCreateObjectFromStringable(): void
    {
        $valueObject = RangeInteger::fromString(new StringableObject('-574,1278'));
        self::assertSame([-574, 1278], $valueObject->toArray());
    }

    public function testCreateObjectFromStringThrowsExceptionIfNullProvided(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        RangeInteger::fromString(null); // @phpstan-ignore argument.type
    }

    public function testCreateObjectFromStringThrowsExceptionIfNoValueProvided(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');
        RangeInteger::fromString(); // @phpstan-ignore arguments.count
    }

    public function testCreateObjectFromStringThrowsExceptionIfEmptyStringProvided(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Range string string cannot be empty.');
        RangeInteger::fromString('');
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new RangeInteger(-5074, -1278);
        self::assertSame('-5074 - -1278', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new RangeInteger(-5074, 1278);
        self::assertSame('-5074 - 1278', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string|int/');
        new RangeInteger(null, null); // @phpstan-ignore argument.type, argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new RangeInteger(); // @phpstan-ignore arguments.count
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(RangeInteger::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(RangeInteger::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new RangeInteger(-5074, 1278);
        $valueObject2 = new RangeInteger(-5074, 1278);
        $valueObject3 = new RangeInteger(774, 888);

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new RangeInteger(-5074, 1278);
        $valueObject2 = new RangeInteger(-5074, 1278);
        $valueObject3 = new RangeInteger(774, 888);

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
