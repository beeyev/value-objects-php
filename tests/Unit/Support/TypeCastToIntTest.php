<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit\Support;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Support\IntegerStringValidator;
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
#[CoversMethod(TypeCast::class, 'toInt')]
#[UsesClass(IntegerStringValidator::class)]
final class TypeCastToIntTest extends AbstractTestCase
{
    #[DataProvider('provideValidIntValues')]
    public function testToIntWithValidValues(int|string|\Stringable $value, string $valueName, int $expectedResult): void
    {
        self::assertSame($expectedResult, TypeCast::toInt($value, $valueName));
    }

    /**
     * @return array<mixed>
     */
    public static function provideValidIntValues(): iterable
    {
        return [
            'Integer' => [31337, 'integer', 31337],
            'String integer' => ['-31337', 'string integer', -31337],
            'Stringable integer' => [new StringableObject('31337'), 'stringable integer', 31337],
        ];
    }

    public function throwExceptionWhenValueIsEmpty(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Value cannot be empty.');
        TypeCast::toInt('', 'Width');
    }

    public function throwExceptionWhenValueIsNotValidInteger(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided Width value is not a valid integer. Given value: `not integer`');
        TypeCast::toInt('not integer', 'Width');
    }

    public function throwExceptionWhenValueIsTooBig(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided Width value is too big and cannot be represented as integer. Given value: `9223372036854775808`');
        TypeCast::toInt('9223372036854775808', 'Width');
    }

    public function throwExceptionWhenValueIsTooSmall(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided Width value is too small and cannot be represented as integer. Given value: `-9223372036854775809`');
        TypeCast::toInt('-9223372036854775809', 'Width');
    }
}
