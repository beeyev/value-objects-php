<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Support\UrlValidator;

/**
 * Value object for URL.
 */
readonly class Url extends Text
{
    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(string $value): void
    {
        parent::validate($value);

        if (!UrlValidator::isUrlValid($value)) {
            throw new ValueObjectInvalidArgumentException("Provided string is not a valid URL. Given value: {$value}");
        }
    }
}
