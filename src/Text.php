<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for text.
 */
readonly class Text extends AbstractValueObject
{
    /** @var non-empty-string */
    public string $value;

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    public function __construct(string|\Stringable $value)
    {
        $value = (string) $value;
        $this->validate($value);
        $this->value = $value;
    }

    /**
     * Returns the length of the text.
     *
     * @return positive-int
     */
    public function length(): int
    {
        return mb_strlen($this->value);
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     *
     * @phpstan-assert non-empty-string $value
     */
    protected function validate(string $value): void
    {
        if ($value === '') {
            throw new ValueObjectInvalidArgumentException('Provided value cannot be empty.');
        }
    }

    /**
     * Returns string representation of the value object.
     *
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
