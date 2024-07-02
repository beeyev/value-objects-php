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
 * @property positive-int $value
 */
readonly class PositiveInteger extends NonNegativeInteger
{
    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(): void
    {
        if ($this->value < 1) {
            throw new ValueObjectInvalidArgumentException("Provided number is not a positive integer. Given value: `{$this->value}`.");
        }
    }
}
