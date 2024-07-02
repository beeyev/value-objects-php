<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Dummy;

final readonly class StringableObject implements \Stringable
{
    private string $defaultValue;

    public function __construct(string $defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    public function __toString(): string
    {
        return $this->defaultValue;
    }
}
