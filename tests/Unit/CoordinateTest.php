<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Support\FloatStringValidator;
use Beeyev\ValueObject\Support\TypeCast;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Coordinate;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(Coordinate::class)]
#[UsesClass(FloatStringValidator::class)]
#[UsesClass(TypeCast::class)]
final class CoordinateTest extends AbstractTestCase
{
    #[DataProvider('validCoordinatesProvider')]
    public function testValidCoordinates(float $latitude, float $longitude): void
    {
        $coordinate = new Coordinate($latitude, $longitude);
        self::assertEquals($latitude, $coordinate->latitude);
        self::assertEquals($longitude, $coordinate->longitude);
        self::assertSame([$latitude, $longitude], $coordinate->toArray());
        self::assertSame("{$latitude}, {$longitude}", (string) $coordinate);
    }

    /**
     * @return array<mixed>
     */
    public static function validCoordinatesProvider(): array
    {
        return [
            'London' => [51.5074, -0.1278],
            'New York' => [40.7128, -74.0060],
            'Sydney' => [-33.8688, 151.2093],
            'Tokyo' => [35.6895, 139.6917],
            'Sao Paulo' => [-23.5505, -46.6333],
            'Intersection of the Prime Meridian and the Equator' => [0.0, 0.0],
            'South Pole' => [-90.0, 0.0],
            'North Pole' => [90.0, 0.0],
            'International Date Line at the Equator' => [0.0, 180.0],
            'International Date Line at the Equator in the opposite direction' => [0.0, -180.0],
        ];
    }

    public function testAcceptsCorrectCoordinateInt(): void
    {
        $valueObject = new Coordinate('90', '-10');
        self::assertSame([90.0, -10.0], $valueObject->toArray());
    }

    public function testAcceptsCorrectCoordinateString(): void
    {
        $valueObject = new Coordinate('51.5074', '-0.1278');
        self::assertSame([51.5074, -0.1278], $valueObject->toArray());
    }

    public function testThrowsExceptionIfLatitudeIsNotNumeric(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided Latitude string is not numeric. Given value: `invalid`');
        new Coordinate('invalid', 0.0);
    }

    public function testThrowsExceptionIfLongitudeIsNotNumeric(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided Longitude string is not numeric. Given value: `invalid`');
        new Coordinate(0.0, 'invalid');
    }

    public function testThrowsExceptionIfLatitudeIsOutOfRange1(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Latitude must be between `-90` and `90` degrees. Given value: `90.1`');
        new Coordinate(90.1, 0.0);
    }

    public function testThrowsExceptionIfLatitudeIsOutOfRange2(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Latitude must be between `-90` and `90` degrees. Given value: `-90.1`');
        new Coordinate(-90.1, 0.0);
    }

    public function testThrowsExceptionIfLongitudeIsOutOfRange1(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Longitude must be between `-180` and `180` degrees. Given value: `180.1`');
        new Coordinate(0.0, 180.1);
    }

    public function testThrowsExceptionIfLongitudeIsOutOfRange2(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Longitude must be between `-180` and `180` degrees. Given value: `-180.1`');
        new Coordinate(0.0, -180.1);
    }

    public function testAcceptsCorrectCoordinateStringable(): void
    {
        $valueObject = new Coordinate(new StringableObject('51.5074'), new StringableObject('-0.1278'));
        self::assertSame([51.5074, -0.1278], $valueObject->toArray());
    }

    #[DataProvider('createObjectFromStringDataProvider')]
    public function testSuccessfullyCreateObjectFromString(string $coordinates): void
    {
        $valueObject = Coordinate::fromString($coordinates);
        self::assertSame([51.5074, -0.1278], $valueObject->toArray());
    }

    /**
     * @return array<mixed>
     */
    public static function createObjectFromStringDataProvider(): array
    {
        return [
            ['51.5074,-0.1278'],
            ['51.5074, -0.1278'],
            ['51.5074 -0.1278'],
            ['51.5074/-0.1278'],
        ];
    }

    #[DataProvider('createInvalidObjectFromStringDataProvider')]
    public function testFailedCreateObjectFromString(string $coordinates): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Coordinate string can not be parsed. Given value:/');
        Coordinate::fromString($coordinates);
    }

    /**
     * @return array<mixed>
     */
    public static function createInvalidObjectFromStringDataProvider(): array
    {
        return [
            ['51.5074-0.1278'],
            ['51.5074 , -0.1278'],
            ['51.5/074 -0.1278'],
            ['51.5074 / -0.1278'],
            ['51.5074'],
        ];
    }

    public function testSuccessfullyCreateObjectFromStringable(): void
    {
        $valueObject = Coordinate::fromString(new StringableObject('51.5074,-0.1278'));
        self::assertSame([51.5074, -0.1278], $valueObject->toArray());
    }

    public function testCreateObjectFromStringThrowsExceptionIfNullProvided(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        Coordinate::fromString(null); // @phpstan-ignore argument.type
    }

    public function testCreateObjectFromStringThrowsExceptionIfNoValueProvided(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');
        Coordinate::fromString(); // @phpstan-ignore arguments.count
    }

    public function testCreateObjectFromStringThrowsExceptionIfEmptyStringProvided(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Coordinate string cannot be empty.');
        Coordinate::fromString('');
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testPropertyIsAccessible(): void
    {
        $valueObject = new Coordinate(51.5074, -0.1278);
        self::assertSame([51.5074, -0.1278], $valueObject->toArray());
    }

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new Coordinate(51.5074, -0.1278);
        self::assertSame(51.5074, $valueObject->latitude);
        self::assertSame(-0.1278, $valueObject->longitude);
        self::assertSame('51.5074, -0.1278', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new Coordinate(51.5074, -0.1278);
        self::assertSame('51.5074, -0.1278', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string|int|bool/');
        new Coordinate(null, null); // @phpstan-ignore argument.type, argument.type
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Value cannot be empty.');
        new Coordinate('', '');
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Coordinate(); // @phpstan-ignore arguments.count
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Coordinate::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Coordinate::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new Coordinate(51.5074, -0.1278);
        $valueObject2 = new Coordinate(51.5074, -0.1278);
        $valueObject3 = new Coordinate(35.6895, 139.6917);

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new Coordinate(51.5074, -0.1278);
        $valueObject2 = new Coordinate(51.5074, -0.1278);
        $valueObject3 = new Coordinate(35.6895, 139.6917);

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
