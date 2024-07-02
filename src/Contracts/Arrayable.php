<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Contracts;

interface Arrayable
{
    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
