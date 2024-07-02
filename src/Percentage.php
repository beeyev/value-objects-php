<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for percentage numbers.
 *
 * @property non-negative-int $value
 */
readonly class Percentage extends NonNegativeInteger
{
    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(): void
    {
        if ($this->value < 0 || $this->value > 100) {
            throw new ValueObjectInvalidArgumentException("Provided number is out of percentage range. Given value: `{$this->value}`.");
        }
    }
}
