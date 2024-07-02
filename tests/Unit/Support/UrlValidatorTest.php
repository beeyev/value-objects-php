<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit\Support;

use Beeyev\ValueObject\Support\UrlValidator;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(UrlValidator::class)]
final class UrlValidatorTest extends AbstractTestCase
{
    #[DataProvider('provideSuccessfullyValidatesUrlCases')]
    public function testSuccessfullyValidatesUrl(string $url): void
    {
        self::assertTrue(UrlValidator::isUrlValid($url));
    }

    /**
     * @return array<mixed>
     */
    public static function provideSuccessfullyValidatesUrlCases(): iterable
    {
        return [
            'GitHub' => ['https://github.com'],
            'StackOverflow' => ['https://stackoverflow.com'],
            'FTP Protocol' => ['ftp://ftp.example.com'],
            'File Protocol' => ['file://path/to/file.txt'],
            'Multiple Domains' => ['http://www.subdomain.example.com'],
            'URL with Query' => ['http://www.example.com/path?name=John&age=30'],
            'URL with Fragment' => ['http://www.example.com/path#section'],
        ];
    }

    #[DataProvider('provideFailsToValidateUrlCases')]
    public function testFailsToValidateUrl(string $url): void
    {
        self::assertFalse(UrlValidator::isUrlValid($url));
    }

    /**
     * @return array<mixed>
     */
    public static function provideFailsToValidateUrlCases(): iterable
    {
        return [
            'Missing Protocol' => ['www.google.com'],
            'Missing Domain' => ['https:///path/to/file'],
            'Invalid Protocol' => ['htp://www.example.com'],
            'Invalid Domain' => ['http://.com'],
            'URL with Space' => ['http://www.example .com'],
            'URL with Invalid Path' => ['http://www.example.com/<script>'],
        ];
    }

    public function testFailsToValidateIfUrlIsEmpty(): void
    {
        self::assertFalse(UrlValidator::isUrlValid(''));
    }
}
