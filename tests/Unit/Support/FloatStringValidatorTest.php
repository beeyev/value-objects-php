<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit\Support;

use Beeyev\ValueObject\Support\FloatStringValidator;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(FloatStringValidator::class)]
final class FloatStringValidatorTest extends AbstractTestCase
{
    #[DataProvider('provideValidFloatStrings')]
    public function testValidFloatStrings(string $value): void
    {
        self::assertTrue(FloatStringValidator::isValid($value));
    }

    /**
     * @return array<mixed>
     */
    public static function provideValidFloatStrings(): iterable
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
            ['0.0'],
            ['1.0'],
            ['-1.0'],
            ['1234567890.1234567890'],
            ['-1234567890.1234567890'],
            ['+1234567890.1234567890'],
            ['+39223372036854775808.39223372036854775808'],
            ['-39223372036854775808.39223372036854775808'],
        ];
    }

    #[DataProvider('provideInvalidFloatStrings')]
    public function testInvalidFloatStrings(string $value): void
    {
        self::assertFalse(FloatStringValidator::isValid($value));
    }

    /**
     * @return array<mixed>
     */
    public static function provideInvalidFloatStrings(): iterable
    {
        return [
            [''],
            [' '],
            ['abc'],
            ['1.0.0'],
            ['-1.0.0'],
            ['+1.0.0'],
            ['3.14.15'],
            ['123abc'],
            ['abc123'],
        ];
    }
}
