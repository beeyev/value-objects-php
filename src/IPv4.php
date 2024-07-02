<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for IPv4.
 */
readonly class IPv4 extends Text
{
    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(string $value): void
    {
        parent::validate($value);

        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            throw new ValueObjectInvalidArgumentException("Provided string is not a valid IPv4 address. Given value: `{$value}`.");
        }
    }
}
