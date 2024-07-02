<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Email;
use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Support\EmailAddressValidator;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(Email::class)]
#[UsesClass(EmailAddressValidator::class)]
final class EmailTest extends AbstractTestCase
{
    public function testAcceptsCorrectEmailAddress(): void
    {
        $valueObject = new Email('abc@gmail.com');
        self::assertSame('abc@gmail.com', $valueObject->value);
        self::assertSame('abc', $valueObject->username);
        self::assertSame('gmail.com', $valueObject->domain);
    }

    #[DataProvider('provideFailsWithIncorrectEmailCases')]
    public function testFailsWithIncorrectEmail(string $incorrectEmailAddress): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Provided email address is incorrect. Given value:/');
        new Email($incorrectEmailAddress);
    }

    /**
     * @return array<mixed>
     */
    public static function provideFailsWithIncorrectEmailCases(): iterable
    {
        return [
            ['abc'],
            ['abc@'],
            ['abc@.com'],
            ['abc@com'],
            ['abc.com'],
            ['@gmail.com'],
            ['abc@.'],
            ['abc@.com'],
            ['abc@com'],
        ];
    }

    public function testAcceptsCorrectClassStringAsStringableObject(): void
    {
        $valueObject = new Email(new StringableObject('hello@gmail.com'));
        self::assertSame('hello@gmail.com', $valueObject->value);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new Email('hello@gmail.com');
        self::assertSame('hello@gmail.com', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new Email('hello@gmail.com');
        self::assertSame('hello@gmail.com', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        new Email(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Email(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringableObjectGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Email(new StringableObject(''));
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Email('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Email::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Email::class);

        foreach (['validate', 'extractUsername', 'extractDomain'] as $method) {
            self::assertTrue($reflection->getMethod($method)->isProtected());
        }
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new Email('abc@abc.com');
        $valueObject2 = new Email('abc@abc.com');
        $valueObject3 = new Email('abc@abc22.com');

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new Email('abc@abc.com');
        $valueObject2 = new Email('abc@abc.com');
        $valueObject3 = new Email('abc@abc22.com');

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }

    public function testLength(): void
    {
        $valueObject = new Email('abc@abc.com');
        self::assertSame(11, $valueObject->length());
    }
}
