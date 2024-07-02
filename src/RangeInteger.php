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
 * Value object for range of integer numbers.
 */
readonly class RangeInteger extends AbstractValueObject implements Arrayable
{
    protected const DELIMITERS = [', ', ',', ' ', ' - ', '..', '/'];

    public int $start;
    public int $end;

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    public function __construct(int|string|\Stringable $start, int|string|\Stringable $end)
    {
        $start = TypeCast::toInt($start, 'Start');
        $end = TypeCast::toInt($end, 'End');

        $this->validate($start, $end);

        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return array{0: int, 1: int}
     */
    public function toArray(): array
    {
        return [$this->start, $this->end];
    }

    /**
     * Converts string to RangeInteger object.
     * Supported delimiters: @see static::DELIMITERS
     */
    public static function fromString(string|\Stringable $range): self
    {
        $range = (string) $range;

        if ($range === '') {
            throw new ValueObjectInvalidArgumentException('Range string string cannot be empty.');
        }

        $normalized = str_replace(static::DELIMITERS, '/', $range);

        $rangeParts = explode('/', $normalized);

        if (count($rangeParts) !== 2) {
            throw new ValueObjectInvalidArgumentException("Range string can not be parsed. Given value: `{$range}`.");
        }

        return new self($rangeParts[0], $rangeParts[1]);
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    protected function validate(int $start, int $end): void
    {
        if ($start > $end) {
            throw new ValueObjectInvalidArgumentException("Range Start value cannot be greater than End value. Given values: `{$start}` and `{$end}`");
        }
    }

    public function __toString(): string
    {
        return $this->start . ' - ' . $this->end;
    }
}
