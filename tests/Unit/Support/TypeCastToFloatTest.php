<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit\Support;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Support\FloatStringValidator;
use Beeyev\ValueObject\Support\TypeCast;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(TypeCast::class)]
#[CoversMethod(TypeCast::class, 'toFloat')]
#[UsesClass(FloatStringValidator::class)]
final class TypeCastToFloatTest extends AbstractTestCase
{
    #[DataProvider('provideValidFloatValues')]
    public function testToFloatWithValidValues(float|int|string|\Stringable $value, string $valueName, float $expectedResult): void
    {
        self::assertSame($expectedResult, TypeCast::toFloat($value, $valueName));
    }

    /**
     * @return array<string, array{0: float|int|string|\Stringable, 1: string, 2: float}>
     */
    public static function provideValidFloatValues(): iterable
    {
        return [
            'Float' => [313.37, 'float', 313.37],
            'Integer' => [31337, 'integer', 31337.0],
            'String float' => ['313.37', 'string float', 313.37],
            'String integer' => ['31337', 'string integer', 31337.0],
            'Stringable float' => [new StringableObject('313.37'), 'stringable float', 313.37],
            'Stringable integer' => [new StringableObject('31337'), 'stringable integer', 31337.0],
        ];
    }

    public function throwExceptionWhenValueIsEmpty(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Value cannot be empty.');
        TypeCast::toFloat('', 'Width');
    }

    public function throwExceptionWhenValueIsNotNumeric(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided Width string is not numeric. Given value: `not numeric`');
        TypeCast::toFloat('not numeric', 'Width');
    }

    public function throwExceptionWhenValueCannotBeRepresentedAsFloat(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided Width cannot be represented as float. Given value: `9223372036854775808`');
        TypeCast::toFloat('9223372036854775808', 'Width');
    }
}
