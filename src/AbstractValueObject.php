<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

abstract readonly class AbstractValueObject implements \Stringable
{
    /**
     * Returns true if the value object is equal to another value object.
     */
    public function sameAs(self $object): bool
    {
        return $this == $object; // @phpstan-ignore equal.notAllowed
    }

    /**
     * Returns true if the value object is not equal to another value object.
     */
    public function notSameAs(self $object): bool
    {
        return !$this->sameAs($object);
    }

    /**
     * Returns string representation of the value object.
     *
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->__toString(); // @phpstan-ignore return.type
    }
}
