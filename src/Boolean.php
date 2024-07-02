<?php
/**
 * @author Alexander Tebiev -  https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for boolean values.
 */
readonly class Boolean extends AbstractValueObject
{
    protected const TRUE_VALUES = [
        '1' => true,
        'true' => true,
        'on' => true,
        'yes' => true,
    ];
    protected const FALSE_VALUES = [
        '0' => true,
        'false' => true,
        'off' => true,
        'no' => true,
    ];

    public bool $value;

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    public function __construct(bool|int|string|\Stringable $value)
    {
        if ($value instanceof \Stringable) {
            $value = (string) $value;
        }

        $this->validate($value);

        $this->value = is_bool($value) ? $value : $this->convertToBoolean($value);
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    protected function convertToBoolean(int|string $value): bool
    {
        $value = strtolower((string) $value);

        if (isset(static::TRUE_VALUES[$value])) {
            return true;
        }

        if (isset(static::FALSE_VALUES[$value])) {
            return false;
        }

        throw new ValueObjectInvalidArgumentException("Value could not be converted to boolean, given value: `{$value}`.");
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    protected function validate(bool|int|string $value): void
    {
        if ($value === '') {
            throw new ValueObjectInvalidArgumentException('Provided string value cannot be empty.');
        }
    }

    /**
     * Returns string representation of the value object.
     *
     * @return non-empty-string
     */
    #[\Override]
    public function __toString(): string
    {
        return $this->value ? 'true' : 'false';
    }
}
