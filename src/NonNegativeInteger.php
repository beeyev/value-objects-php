<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for non-negative integer numbers.
 *
 * @property non-negative-int $value
 */
readonly class NonNegativeInteger extends Integer
{
    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(): void
    {
        if ($this->value < 0) {
            throw new ValueObjectInvalidArgumentException("Provided number is not a non-negative integer. Given value: `{$this->value}`.");
        }
    }
}
