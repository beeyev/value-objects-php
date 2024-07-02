<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Resolution;
use Beeyev\ValueObject\Support\IntegerStringValidator;
use Beeyev\ValueObject\Support\TypeCast;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(Resolution::class)]
#[UsesClass(IntegerStringValidator::class)]
#[UsesClass(TypeCast::class)]
class ResolutionTest extends AbstractTestCase
{
    public function testConstructorValidValuesAsInt(): void
    {
        $resolution = new Resolution(1920, 1080);
        self::assertSame(1920, $resolution->width);
        self::assertSame(1080, $resolution->height);
    }

    public function testConstructorValidValuesAsString(): void
    {
        $resolution = new Resolution('1920', '1080');
        self::assertSame(1920, $resolution->width);
        self::assertSame(1080, $resolution->height);
    }

    public function testConstructorValidValuesAsStringableObject(): void
    {
        $resolution = new Resolution(new StringableObject('1920'), new StringableObject('1080'));
        self::assertSame(1920, $resolution->width);
        self::assertSame(1080, $resolution->height);
    }

    #[DataProvider('fromStringValidValuesDataProvider')]
    public function testFromStringValidValues(string $value, int $expectedWidth, int $expectedHeight): void
    {
        $resolution = Resolution::fromString($value);
        self::assertSame($expectedWidth, $resolution->width);
        self::assertSame($expectedHeight, $resolution->height);
    }

    /**
     * @return array<mixed>
     */
    public static function fromStringValidValuesDataProvider(): array
    {
        return [
            ['1920x1080', 1920, 1080],
            ['1920/1080', 1920, 1080],
            ['1920 1080', 1920, 1080],
            ['1920,1080', 1920, 1080],
            ['1920, 1080', 1920, 1080],
        ];
    }

    public function testFromStringThrowsExceptionIfEmptyValue(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Range string string cannot be empty.');
        Resolution::fromString('');
    }

    public function testFromStringThrowsExceptionIfInvalidValue(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Resolution string can not be parsed. Given value:/');
        Resolution::fromString('1920#1080');
    }

    public function testToArray(): void
    {
        $resolution = new Resolution(1920, 1080);
        self::assertEquals([1920, 1080], $resolution->toArray());
    }

    public function testThrowsExceptionIfInvalidWidth(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Width value must be a positive integer. Given value: `-10`');
        new Resolution(-10, 1080);
    }

    public function testThrowsExceptionIfInvalidHeight(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Height value must be a positive integer. Given value: `-10`');
        new Resolution(1920, -10);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new Resolution(122, 500);
        self::assertSame('122x500', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new Resolution(122, 500);
        self::assertSame('122x500', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string|int/');
        new Resolution(null, null); // @phpstan-ignore argument.type, argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Resolution(); // @phpstan-ignore arguments.count
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Resolution::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Resolution::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new Resolution(100, 200);
        $valueObject2 = new Resolution(100, 200);
        $valueObject3 = new Resolution(444, 555);

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new Resolution(100, 200);
        $valueObject2 = new Resolution(100, 200);
        $valueObject3 = new Resolution(444, 555);

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
