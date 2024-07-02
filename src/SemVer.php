<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
declare(strict_types=1);

namespace Beeyev\ValueObject;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;

/**
 * Value object for Semantic Version.
 */
readonly class SemVer extends Text
{
    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     */
    protected const SEMVER_VALIDATION_REGEX = '/^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)\.(?P<patch>0|[1-9]\d*)(?:-(?P<preRelease>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+(?P<buildmetadata>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';

    /** @var non-negative-int */
    public int $major;

    /** @var non-negative-int */
    public int $minor;

    /** @var non-negative-int */
    public int $patch;

    public string $preRelease;

    public string $build;

    /**
     * Returns the release version major.minor.patch
     * This string contains only the major, minor, and patch versions, excluding any pre-release and build metadata.
     *
     * @return non-empty-string
     */
    public string $releaseVersion;

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    public function __construct(string|\Stringable $value)
    {
        $value = $this->trimVPrefix((string) $value);
        parent::__construct($value);

        $match = $this->matchSemVer($this->value);

        $this->major = (int) $match['major']; // @phpstan-ignore assign.propertyType
        $this->minor = (int) $match['minor']; // @phpstan-ignore assign.propertyType
        $this->patch = (int) $match['patch']; // @phpstan-ignore assign.propertyType
        $this->preRelease = $match['preRelease'] ?? '';
        $this->build = $match['buildmetadata'] ?? '';

        $this->releaseVersion = $this->major . '.' . $this->minor . '.' . $this->patch;
    }

    /**
     * Returns true if the version is equal to the provided version.
     */
    public function equalTo(self $semVer): bool
    {
        return version_compare($this->releaseVersion, $semVer->releaseVersion, '==');
    }

    /**
     * Returns true if the version is not equal to the provided version.
     */
    public function notEqualTo(self $semVer): bool
    {
        return version_compare($this->releaseVersion, $semVer->releaseVersion, '!=');
    }

    /**
     * Returns true if the version is lower than the provided version.
     */
    public function lowerThan(self $semVer): bool
    {
        return version_compare($this->releaseVersion, $semVer->releaseVersion, '<');
    }

    /**
     * Returns true if the version is lower than or equal to the provided version.
     */
    public function lowerThanOrEqualTo(self $semVer): bool
    {
        return version_compare($this->releaseVersion, $semVer->releaseVersion, '<=');
    }

    /**
     * Returns true if the version is greater than the provided version.
     */
    public function greaterThan(self $semVer): bool
    {
        return version_compare($this->releaseVersion, $semVer->releaseVersion, '>');
    }

    /**
     * Returns true if the version is greater than or equal to the provided version.
     */
    public function greaterThanOrEqualTo(self $semVer): bool
    {
        return version_compare($this->releaseVersion, $semVer->releaseVersion, '>=');
    }

    /**
     * Trims the 'v' prefix if exists from the semver.
     */
    protected function trimVPrefix(string $inputValue): string
    {
        if ($inputValue === '') {
            return $inputValue;
        }

        if (mb_stripos($inputValue, 'v') === 0) {
            return mb_substr($inputValue, 1);
        }

        return $inputValue;
    }

    /**
     * @throws ValueObjectInvalidArgumentException
     */
    #[\Override]
    protected function validate(string $value): void
    {
        parent::validate($value);

        $this->matchSemVer($value);
    }

    /**
     * @param non-empty-string $semVer
     *
     * @return non-empty-array<non-empty-string|non-negative-int, non-empty-string>
     *
     * @throws ValueObjectInvalidArgumentException
     */
    protected function matchSemVer(string $semVer): array
    {
        if (!preg_match(self::SEMVER_VALIDATION_REGEX, $semVer, $match)) { // @phpstan-ignore booleanNot.exprNotBoolean
            throw new ValueObjectInvalidArgumentException("Provided string is not a valid semantic version. Given value: `{$semVer}`");
        }

        return $match; // @phpstan-ignore return.type
    }
}
