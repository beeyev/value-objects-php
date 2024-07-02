<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\IPv6;
use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(IPv6::class)]
final class IPv6Test extends AbstractTestCase
{
    #[DataProvider('provideAcceptsValidIPv6Cases')]
    public function testAcceptsValidIPv6(string $IPv6Address): void
    {
        $valueObject = new IPv6($IPv6Address);
        self::assertSame($IPv6Address, $valueObject->value);
    }

    /**
     * @return array<mixed>
     */
    public static function provideAcceptsValidIPv6Cases(): iterable
    {
        return [
            'Localhost' => ['::1'],
            'Google DNS' => ['2001:4860:4860::8888'],
            'Cloudflare DNS' => ['2606:4700:4700::1111'],
            'IPv6 with Double Colon' => ['2001:db8::2:1'],
            'IPv6 Full Form' => ['2001:0db8:0000:0000:0000:ff00:0042:8329'],
        ];
    }

    #[DataProvider('provideFailsWithIncorrectIPv6Cases')]
    public function testFailsWithIncorrectIPv6(string $IPv6Address): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Provided string is not a valid IPv6 address. Given value:/');
        new IPv6($IPv6Address);
    }

    /**
     * @return array<mixed>
     */
    public static function provideFailsWithIncorrectIPv6Cases(): iterable
    {
        return [
            'IPv6 Address' => ['192.168.0.1'],
            'Invalid IPv6' => ['2001:db8:::1'],
            'IPv6 with Extra Period' => ['2001:db8::.1'],
            'IPv6 with Invalid Characters' => ['2001:db8::abcg'],
            'IPv6 with More than 8 Groups' => ['2001:0db8:85a3:0000:0000:8a2e:0370:7334:1'],
            'IPv6 with Less than 8 Groups' => ['2001:0db8:85a3:0000:8a2e:0370'],
            'random string' => ['blah blah blah'],
            'random int' => ['312'],
            'random float' => ['3.14'],
        ];
    }

    public function testAcceptsNonEmptyStringableObject(): void
    {
        $valueObject = new IPv6(new StringableObject('2606:4700:4700::1111'));
        self::assertSame('2606:4700:4700::1111', $valueObject->value);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testPropertyIsAccessible(): void
    {
        $valueObject = new IPv6('2606:4700:4700::1111');
        self::assertSame('2606:4700:4700::1111', $valueObject->value);
    }

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new IPv6('2606:4700:4700::1111');
        self::assertSame('2606:4700:4700::1111', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new IPv6('2606:4700:4700::1111');
        self::assertSame('2606:4700:4700::1111', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        new IPv6(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new IPv6(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringableObjectGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new IPv6(new StringableObject(''));
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new IPv6('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(IPv6::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(IPv6::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new IPv6('2606:4700:4700::1111');
        $valueObject2 = new IPv6('2606:4700:4700::1111');
        $valueObject3 = new IPv6('2606:4700:4100::1111');

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new IPv6('2606:4700:4700::1111');
        $valueObject2 = new IPv6('2606:4700:4700::1111');
        $valueObject3 = new IPv6('2606:4700:4100::1111');

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
