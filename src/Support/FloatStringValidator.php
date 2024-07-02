<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject\Support;

/**
 * @internal
 */
final readonly class FloatStringValidator
{
    public static function isValid(string $value): bool
    {
        return preg_match('/^[-+]?(\d*[.])?\d+$/', $value) > 0;
    }
}
