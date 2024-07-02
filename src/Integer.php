<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

/**
 * Value object for integer numbers.
 */
readonly class Integer extends AbstractValueObject
{
    public function __construct(public int $value)
    {
        $this->validate();
    }

    protected function validate(): void {}

    /**
     * Returns string representation of the value object.
     *
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
