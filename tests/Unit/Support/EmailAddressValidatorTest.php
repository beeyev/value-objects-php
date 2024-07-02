<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit\Support;

use Beeyev\ValueObject\Support\EmailAddressValidator;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(EmailAddressValidator::class)]
final class EmailAddressValidatorTest extends AbstractTestCase
{
    #[DataProvider('provideValidEmailAddressesCases')]
    public function testValidEmailAddresses(string $email): void
    {
        self::assertTrue(EmailAddressValidator::isEmailAddressValid($email));
    }

    /**
     * @return array<mixed>
     */
    public static function provideValidEmailAddressesCases(): iterable
    {
        return [
            ['abc@gmail.com'],
            ['john.doe@example.com'],
            ['test.email+alex@leetcode.com'],
            ['test.email.leet+alex@code.com'],
        ];
    }

    #[DataProvider('provideInvalidEmailAddressesCases')]
    public function testInvalidEmailAddresses(string $email): void
    {
        self::assertFalse(EmailAddressValidator::isEmailAddressValid($email));
    }

    /**
     * @return array<mixed>
     */
    public static function provideInvalidEmailAddressesCases(): iterable
    {
        return [
            ['abc@.com'],
            ['abc@com'],
            ['abc.com'],
            ['@gmail.com'],
            ['abc@.'],
            ['abc@.com'],
            ['abc@com'],
            [' abc@com '],
            ['abc@com  '],
            [' abc@com'],
            ["\nabc@com\n"],
            ["nabc@com\n"],
        ];
    }

    public function testReturnsFalseWithLongUsername(): void
    {
        self::assertFalse(EmailAddressValidator::isEmailAddressValid(str_repeat('a', 65) . '@gmail.com'));
    }

    public function testReturnsFalseWithLongDomain(): void
    {
        self::assertFalse(EmailAddressValidator::isEmailAddressValid('abc@' . str_repeat('b', 256)));
    }
}
