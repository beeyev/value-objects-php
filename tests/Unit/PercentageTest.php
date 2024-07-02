<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Percentage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(Percentage::class)]
final class PercentageTest extends AbstractTestCase
{
    #[DataProvider('integerDataProvider')]
    public function testAcceptsCorrectInputValue(int $value): void
    {
        $valueObject = new Percentage($value);
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
            [99],
            [100],
        ];
    }

    public function testDoesNotAcceptNegativeInputValue(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided number is out of percentage range. Given value: `-1`.');
        new Percentage(-1);
    }

    public function testDoesNotAcceptOutOfRangeInputValue(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided number is out of percentage range. Given value: `101`.');
        new Percentage(101);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new Percentage(55);
        self::assertSame('55', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new Percentage(55);
        self::assertSame('55', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type int/');
        new Percentage(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Percentage(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type int/');
        new Percentage(''); // @phpstan-ignore argument.type
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Percentage::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Percentage::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new Percentage(55);
        $valueObject2 = new Percentage(55);
        $valueObject3 = new Percentage(11);

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new Percentage(55);
        $valueObject2 = new Percentage(55);
        $valueObject3 = new Percentage(11);

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
