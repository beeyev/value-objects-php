<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for class-strings.
 */
readonly class ClassString extends Text
{
    /**
     * Returns true if the class exists for this class string.
     */
    public function isClassExist(): bool
    {
        return class_exists($this->value);
    }

    /**
     * Returns true if the object is an instance of this class string.
     */
    public function isInstanceOf(object|string $object): bool
    {
        return is_a($this->value, is_object($object) ? $object::class : $object, true);
    }

    /**
     * Instantiate the class string without constructor parameters if possible.
     */
    public function instantiate(): object
    {
        $classString = $this->value;

        return new $classString();
    }

    /**
     * Instantiate the class string with constructor parameters if possible.
     *
     * @param mixed ...$parameters
     */
    public function instantiateWith(... $parameters): object
    {
        $classString = $this->value;

        return new $classString(...$parameters);
    }

    /**
     * Returns true if the interface exists for this class string.
     */
    public function isInterfaceExist(): bool
    {
        return interface_exists($this->value);
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(string $value): void
    {
        parent::validate($value);

        if (preg_match('/^\d/', $value) === 1) {
            throw new ValueObjectInvalidArgumentException("Class string value cannot start with a digit. Given value: `{$value}`");
        }

        if (str_contains($value, ' ')) {
            throw new ValueObjectInvalidArgumentException("Class string value cannot contain spaces. Given value: `{$value}`");
        }
    }
}
