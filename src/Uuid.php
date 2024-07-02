<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for UUID.
 */
readonly class Uuid extends Text
{
    /**
     * Uuid validation pattern @see https://github.com/laravel/framework/blob/4ebbabe4eb13870bb43767eefb7e04668b539aaf/src/Illuminate/Support/Str.php#L539
     */
    private const UUID_VALIDATION_REGEX = '/^[\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}$/D';

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(string $value): void
    {
        parent::validate($value);

        // @phpstan-ignore booleanNot.exprNotBoolean
        if (!preg_match(self::UUID_VALIDATION_REGEX, $value)) {
            throw new ValueObjectInvalidArgumentException("Provided string is not valid UUID. Given value: `{$value}`");
        }
    }
}
