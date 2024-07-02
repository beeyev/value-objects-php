<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit\Support;

use Beeyev\ValueObject\Support\IntegerStringValidator;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(IntegerStringValidator::class)]
final class IntegerStringValidatorTest extends AbstractTestCase
{
    #[DataProvider('provideValidIntegerStrings')]
    public function testValidIntegerStrings(string $value): void
    {
        self::assertTrue(IntegerStringValidator::isValid($value));
    }

    /**
     * @return array<mixed>
     */
    public static function provideValidIntegerStrings(): iterable
    {
        return [
            ['0'],
            ['1'],
            ['-1'],
            ['1234567890'],
            ['-1234567890'],
            ['+1234567890'],
            ['+39223372036854775808'],
            ['-39223372036854775808'],
        ];
    }

    #[DataProvider('provideInvalidIntegerStrings')]
    public function testInvalidIntegerStrings(string $value): void
    {
        self::assertFalse(IntegerStringValidator::isValid($value));
    }

    /**
     * @return array<mixed>
     */
    public static function provideInvalidIntegerStrings(): iterable
    {
        return [
            [''],
            [' '],
            ['abc'],
            ['1.0'],
            ['-1.0'],
            ['+1.0'],
            ['3.14'],
            ['123abc'],
            ['abc123'],
        ];
    }
}
