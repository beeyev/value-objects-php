<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Timestamp;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(Timestamp::class)]
final class TimestampTest extends AbstractTestCase
{
    #[DataProvider('timestampDataProvider')]
    public function testAcceptsCorrectInputValue(int $value): void
    {
        $valueObject = new Timestamp($value);
        self::assertSame($value, $valueObject->value);
    }

    /**
     * @return array<mixed>
     */
    public static function timestampDataProvider(): array
    {
        return [
            [time()], // current timestamp
            [strtotime('2022-01-01 00:00:00')], // specific date
            [strtotime('-1 day')], // 1 day ago
            [strtotime('+1 day')], // 1 day in the future
            [strtotime('-1 month')], // 1 month ago
            [strtotime('+1 month')], // 1 month in the future
            [strtotime('-1 year')], // 1 year ago
            [strtotime('+1 year')], // 1 year in the future
        ];
    }

    public function testCorrectDateTimeReturned(): void
    {
        $timestamp = time();
        $dateTime = new \DateTimeImmutable('@' . $timestamp);

        $valueObject = new Timestamp($timestamp);
        self::assertSame($timestamp, $valueObject->value);
        self::assertEquals($dateTime, $valueObject->dateTime);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $timestamp = time();
        $valueObject = new Timestamp($timestamp);
        self::assertSame((string) $timestamp, (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $timestamp = time();

        $valueObject = new Timestamp($timestamp);
        self::assertSame((string) $timestamp, $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type int/');
        new Timestamp(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Timestamp(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type int/');
        new Timestamp(''); // @phpstan-ignore argument.type
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Timestamp::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Timestamp::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new Timestamp(555);
        $valueObject2 = new Timestamp(555);
        $valueObject3 = new Timestamp(111);

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new Timestamp(555);
        $valueObject2 = new Timestamp(555);
        $valueObject3 = new Timestamp(111);

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
