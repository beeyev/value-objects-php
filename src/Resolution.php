<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Contracts\Arrayable;
use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Support\TypeCast;

/**
 * Value object for resolution.
 */
readonly class Resolution extends AbstractValueObject implements Arrayable
{
    protected const DELIMITERS = [', ', ',', ' ', 'x', '/'];

    /** @var positive-int */
    public int $width;

    /** @var positive-int */
    public int $height;

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    public function __construct(int|string|\Stringable $width, int|string|\Stringable $height)
    {
        $width = TypeCast::toInt($width, 'Width');
        $height = TypeCast::toInt($height, 'Height');

        $this->validate($width, $height);

        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Converts string to RangeInteger object.
     * Supported delimiters: @see static::DELIMITERS
     */
    public static function fromString(string|\Stringable $resolution): self
    {
        $resolution = (string) $resolution;

        if ($resolution === '') {
            throw new ValueObjectInvalidArgumentException('Range string string cannot be empty.');
        }

        $normalized = str_ireplace(static::DELIMITERS, '/', $resolution);

        $resolutionParts = explode('/', $normalized);

        if (count($resolutionParts) !== 2) {
            throw new ValueObjectInvalidArgumentException("Resolution string can not be parsed. Given value: `{$resolution}`.");
        }

        return new self($resolutionParts[0], $resolutionParts[1]);
    }

    /**
     * @return array{0: positive-int, 1: positive-int}
     */
    public function toArray(): array
    {
        return [$this->width, $this->height];
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     *
     * @phpstan-assert positive-int $width
     * @phpstan-assert positive-int $height
     */
    protected function validate(int $width, int $height): void
    {
        if ($width <= 0) {
            throw new ValueObjectInvalidArgumentException("Width value must be a positive integer. Given value: `{$width}`");
        }

        if ($height <= 0) {
            throw new ValueObjectInvalidArgumentException("Height value must be a positive integer. Given value: `{$height}`");
        }
    }

    public function __toString(): string
    {
        return $this->width . 'x' . $this->height;
    }
}
