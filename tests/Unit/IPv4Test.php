<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\IPv4;
use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(IPv4::class)]
final class IPv4Test extends AbstractTestCase
{
    #[DataProvider('provideAcceptsValidIPv4Cases')]
    public function testAcceptsValidIPv4(string $ipV4Address): void
    {
        $valueObject = new IPv4($ipV4Address);
        self::assertSame($ipV4Address, $valueObject->value);
    }

    /**
     * @return array<mixed>
     */
    public static function provideAcceptsValidIPv4Cases(): iterable
    {
        return [
            'Localhost' => ['127.0.0.1'],
            'Google DNS' => ['8.8.8.8'],
            'Cloudflare DNS' => ['1.1.1.1'],
            'localhost' => ['127.0.0.1'],
            'Private Network (Class A)' => ['10.0.0.1'],
            'Private Network (Class B)' => ['172.16.0.1'],
            'Private Network (Class C)' => ['192.168.0.1'],
        ];
    }

    #[DataProvider('provideFailsWithIncorrectIPv4Cases')]
    public function testFailsWithIncorrectIPv4(string $ipV4Address): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Provided string is not a valid IPv4 address. Given value:/');
        new IPv4($ipV4Address);
    }

    /**
     * @return array<mixed>
     */
    public static function provideFailsWithIncorrectIPv4Cases(): iterable
    {
        return [
            'Less than 4 octets' => ['192.168.0'],
            'More than 4 octets' => ['192.168.0.1.1'],
            'Octet out of range' => ['192.168.0.256'],
            'Octet negative' => ['192.168.0.-1'],
            'Non-numeric octet' => ['192.168.0.a'],
            'Extra period' => ['192.168.0..1'],
            'Trailing period' => ['192.168.0.1.'],
            'Leading period' => ['.192.168.0.1'],
            'Space in IP' => ['192. 168.0.1'],
            'IP v6' => ['2001:4860:4860::8888'],
            'random string' => ['blah blah blah'],
            'random int' => ['312'],
            'random float' => ['3.14'],
        ];
    }

    public function testAcceptsNonEmptyStringableObject(): void
    {
        $valueObject = new IPv4(new StringableObject('172.16.0.1'));
        self::assertSame('172.16.0.1', $valueObject->value);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new IPv4('172.16.0.1');
        self::assertSame('172.16.0.1', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new IPv4('172.16.0.1');
        self::assertSame('172.16.0.1', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        new IPv4(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new IPv4(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringableObjectGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new IPv4(new StringableObject(''));
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new IPv4('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(IPv4::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(IPv4::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new IPv4('192.186.1.2');
        $valueObject2 = new IPv4('192.186.1.2');
        $valueObject3 = new IPv4('192.186.1.3');

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new IPv4('192.186.1.2');
        $valueObject2 = new IPv4('192.186.1.2');
        $valueObject3 = new IPv4('192.186.1.3');

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
