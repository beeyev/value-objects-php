<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use Beeyev\ValueObject\Uuid;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(Uuid::class)]
final class UuidTest extends AbstractTestCase
{
    public function testAcceptsCorrectUuid(): void
    {
        $uuid = $this->generateUuidV4();
        $valueObject = new Uuid($uuid);
        self::assertSame($uuid, $valueObject->value);
    }

    public function testAcceptsCorrectUuidAsStringable(): void
    {
        $uuid = $this->generateUuidV4();
        $valueObject = new Uuid(new StringableObject($uuid));
        self::assertSame($uuid, $valueObject->value);
    }

    #[DataProvider('provideThrowsExceptionIfIncorrectUuidGivenCases')]
    public function testThrowsExceptionIfIncorrectUuidGiven(string $incorrectUuid): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Provided string is not valid UUID. Given value:/');
        new Uuid($incorrectUuid);
    }

    /**
     * @return array<mixed>
     */
    public static function provideThrowsExceptionIfIncorrectUuidGivenCases(): iterable
    {
        return [
            'Too long' => ['123456789012345678901234567890123456'],
            'Too short' => ['12345678901234567890123456789012345'],
            'Contains non-hex character Z' => ['12345678-9012-3456-7890-12345678901Z'],
            'Incorrect number of segments' => ['123456789012-3456-7890-123456789012'],
            'Incorrect segment lengths' => ['12345678-9012-3456-78901-23456789012'],
            'Last segment too short' => ['12345678-9012-3456-7890-1234567890'],
            'Contains non-hex character g' => ['g1234567-8901-2345-6789-012345678901'],
            'Ends with a hyphen' => ['12345678-9012-3456-7890-12345678901-'],
            'Starts with a hyphen' => ['-12345678-9012-3456-7890-123456789012'],
            'No hyphens' => ['123456789012345678901234567890123456789012'],
        ];
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $uuid = $this->generateUuidV4();
        $valueObject = new Uuid($uuid);
        self::assertSame($uuid, (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $uuid = $this->generateUuidV4();
        $valueObject = new Uuid($uuid);
        self::assertSame($uuid, $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        new Uuid(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Uuid(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringableObjectGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Uuid(new StringableObject(''));
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Uuid('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Uuid::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Uuid::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $uuid = $this->generateUuidV4();

        $valueObject1 = new Uuid($uuid);
        $valueObject2 = new Uuid($uuid);
        $valueObject3 = new Uuid($this->generateUuidV4());

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $uuid = $this->generateUuidV4();

        $valueObject1 = new Uuid($uuid);
        $valueObject2 = new Uuid($uuid);
        $valueObject3 = new Uuid($this->generateUuidV4());

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }

    /**
     * @return non-empty-string
     */
    private function generateUuidV4(): string
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0F | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3F | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
