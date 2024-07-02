<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for timestamp.
 */
readonly class Timestamp extends Integer
{
    public \DateTimeImmutable $dateTime;

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    public function __construct(int $value)
    {
        parent::__construct($value);

        $this->dateTime = new \DateTimeImmutable('@' . $this->value);
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(): void
    {
        if (strtotime(date('Y-m-d H:i:s', $this->value)) !== $this->value) {
            throw new ValueObjectInvalidArgumentException("Provided value is not valid timestamp. Given value: `{$this->value}`.");
        }
    }
}
