<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for IPv6.
 */
readonly class IPv6 extends Text
{
    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(string $value): void
    {
        parent::validate($value);

        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            throw new ValueObjectInvalidArgumentException("Provided string is not a valid IPv6 address. Given value: `{$value}`.");
        }
    }
}
