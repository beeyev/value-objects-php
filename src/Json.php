<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Contracts\Arrayable;
use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for Json string.
 */
readonly class Json extends Text implements Arrayable
{
    /**
     * Returns the json value as an array.
     *
     * @return non-empty-array<mixed>
     */
    public function toArray(): array
    {
        return json_decode($this->value, true, 512, JSON_THROW_ON_ERROR); // @phpstan-ignore return.type
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(string $value): void
    {
        parent::validate($value);

        try {
            json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new ValueObjectInvalidArgumentException("Provided string is not valid JSON. Given value: `{$value}`");
        }
    }
}
