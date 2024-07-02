<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Support\UrlValidator;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use Beeyev\ValueObject\Url;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(Url::class)]
#[UsesClass(UrlValidator::class)]
final class UrlTest extends AbstractTestCase
{
    private const VALID_URL = 'https://tebiev.com/abc/abc?query=1';

    #[DataProvider('provideAcceptsValidUrlCases')]
    public function testAcceptsValidUrl(string $url): void
    {
        $valueObject = new Url($url);
        self::assertSame($url, $valueObject->value);
    }

    /**
     * @return array<mixed>
     */
    public static function provideAcceptsValidUrlCases(): iterable
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

    #[DataProvider('provideFailsWithIncorrectUrlCases')]
    public function testFailsWithIncorrectUrl(string $url): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Provided string is not a valid URL. Given value:/');
        new Url($url);
    }

    /**
     * @return array<mixed>
     */
    public static function provideFailsWithIncorrectUrlCases(): iterable
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

    public function testAcceptsNonEmptyStringableObject(): void
    {
        $valueObject = new Url(new StringableObject(self::VALID_URL));
        self::assertSame(self::VALID_URL, $valueObject->value);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new Url(self::VALID_URL);
        self::assertSame(self::VALID_URL, (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new Url(self::VALID_URL);
        self::assertSame(self::VALID_URL, $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        new Url(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new Url(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringableObjectGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Url(new StringableObject(''));
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new Url('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Url::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(Url::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new Url(self::VALID_URL);
        $valueObject2 = new Url(self::VALID_URL);
        $valueObject3 = new Url('https://tebiev.nl');

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new Url(self::VALID_URL);
        $valueObject2 = new Url(self::VALID_URL);
        $valueObject3 = new Url('https://tebiev.nl');

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }

    public function testLength(): void
    {
        $valueObject = new Url(self::VALID_URL);
        self::assertSame(mb_strlen(self::VALID_URL), $valueObject->length());
    }
}
