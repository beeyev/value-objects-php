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
 * Value object for coordinate.
 */
readonly class Coordinate extends AbstractValueObject implements Arrayable
{
    protected const DELIMITERS = [', ', ',', ' ', '/'];

    public float $latitude;
    public float $longitude;

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    public function __construct(float|int|string|\Stringable $latitude, float|int|string|\Stringable $longitude)
    {
        $latitude = TypeCast::toFloat($latitude, 'Latitude');
        $longitude = TypeCast::toFloat($longitude, 'Longitude');

        $this->validate($latitude, $longitude);

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Converts string to Coordinate object.
     * Supported delimiters: @see static::DELIMITERS
     */
    public static function fromString(string|\Stringable $latitudeAndLongitude): self
    {
        $latitudeAndLongitudeString = (string) $latitudeAndLongitude;

        if ($latitudeAndLongitudeString === '') {
            throw new ValueObjectInvalidArgumentException('Coordinate string cannot be empty.');
        }

        $normalized = str_ireplace(static::DELIMITERS, '/', $latitudeAndLongitudeString);

        $coordinateParts = explode('/', $normalized);

        if (count($coordinateParts) !== 2) {
            throw new ValueObjectInvalidArgumentException("Coordinate string can not be parsed. Given value: `{$latitudeAndLongitudeString}`.");
        }

        return new self($coordinateParts[0], $coordinateParts[1]);
    }

    /**
     * @return array{0: float, 1: float}
     */
    public function toArray(): array
    {
        return [
            $this->latitude,
            $this->longitude,
        ];
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    protected function validate(float $latitude, float $longitude): void
    {
        if ($latitude < -90.0 || $latitude > 90.0) {
            throw new ValueObjectInvalidArgumentException("Latitude must be between `-90` and `90` degrees. Given value: `{$latitude}`");
        }

        if ($longitude < -180.0 || $longitude > 180.0) {
            throw new ValueObjectInvalidArgumentException("Longitude must be between `-180` and `180` degrees. Given value: `{$longitude}`");
        }
    }

    public function __toString(): string
    {
        return $this->latitude . ', ' . $this->longitude;
    }
}
