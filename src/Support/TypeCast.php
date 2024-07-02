<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Support;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * @internal
 */
final readonly class TypeCast
{
    public static function toInt(int|string|\Stringable $value, string $valueName): int
    {
        if (is_int($value)) {
            return $value;
        }

        $valueString = (string) $value;

        if ($valueString === '') {
            throw new ValueObjectInvalidArgumentException('Value cannot be empty.');
        }

        if (IntegerStringValidator::isValid($valueString) === false) {
            throw new ValueObjectInvalidArgumentException("Provided {$valueName} value is not a valid integer. Given value: `{$valueString}`");
        }

        if (PHP_INT_MAX < $valueString) {
            throw new ValueObjectInvalidArgumentException("Provided {$valueName} value is too big and cannot be represented as integer. Given value: `{$valueString}`");
        }

        if (PHP_INT_MIN > $valueString) {
            throw new ValueObjectInvalidArgumentException("Provided {$valueName} value is too small and cannot be represented as integer. Given value: `{$valueString}`");
        }

        return (int) $valueString;
    }

    public static function toFloat(float|int|string|\Stringable $value, string $valueName): float
    {
        if (is_float($value) || is_int($value)) {
            return (float) $value;
        }

        $valueString = (string) $value;

        if ($valueString === '') {
            throw new ValueObjectInvalidArgumentException('Value cannot be empty.');
        }

        if (FloatStringValidator::isValid($valueString) === false) {
            throw new ValueObjectInvalidArgumentException("Provided {$valueName} string is not numeric. Given value: `{$valueString}`");
        }

        $valueFloat = (float) $valueString;

        if ($valueString !== (string) $valueFloat) {
            throw new ValueObjectInvalidArgumentException("Provided {$valueName} cannot be represented as float. Given value: `{$valueString}`");
        }

        return $valueFloat;
    }
}
