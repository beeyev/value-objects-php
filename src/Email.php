<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Support\EmailAddressValidator;

/**
 * Value object for non-negative integer numbers.
 */
readonly class Email extends Text
{
    /** @var non-empty-string */
    public string $username;

    /** @var non-empty-string */
    public string $domain;

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    public function __construct(string|\Stringable $inputValue)
    {
        parent::__construct($inputValue);

        $this->username = $this->extractUsername();
        $this->domain = $this->extractDomain();
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(string $value): void
    {
        parent::validate($value);

        if (EmailAddressValidator::isEmailAddressValid($value) === false) {
            throw new ValueObjectInvalidArgumentException("Provided email address is incorrect. Given value: `{$value}`");
        }
    }

    /**
     * @return non-empty-string
     */
    protected function extractUsername(): string
    {
        $result = explode('@', $this->value)[0];
        assert($result !== '');

        return $result;
    }

    /**
     * @return non-empty-string
     */
    protected function extractDomain(): string
    {
        $result = explode('@', $this->value)[1];
        assert($result !== '');

        return $result;
    }
}
