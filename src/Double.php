<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

/**
 * Value object for float (double) numbers.
 */
readonly class Double extends AbstractValueObject
{
    public float $value;

    public function __construct(float|int $value)
    {
        if (is_int($value)) {
            $value = (float) $value;
        }

        $this->validate($value);

        $this->value = $value;
    }

    protected function validate(float $inputValue): void {}

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
