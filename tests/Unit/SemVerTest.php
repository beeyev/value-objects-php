<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use Beeyev\ValueObject\SemVer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
#[CoversClass(SemVer::class)]
final class SemVerTest extends AbstractTestCase
{
    #[DataProvider('provideValidSemVerStrings')]
    public function testAcceptsCorrectSemVer(
        string $semVer,
        int $expectedMajor,
        int $expectedMinor,
        int $expectedPatch,
        string $expectedPrerelease,
        string $expectedBuild,
        string $expectedReleaseVersion,
    ): void {
        $valueObject = new SemVer($semVer);
        self::assertSame($semVer, $valueObject->value);
        self::assertSame($expectedMajor, $valueObject->major);
        self::assertSame($expectedMinor, $valueObject->minor);
        self::assertSame($expectedPatch, $valueObject->patch);
        self::assertSame($expectedPrerelease, $valueObject->preRelease);
        self::assertSame($expectedBuild, $valueObject->build);
        self::assertSame($expectedReleaseVersion, $valueObject->releaseVersion);
    }

    /**
     * @return array<mixed>
     */
    public static function provideValidSemVerStrings(): array
    {
        return [
            [
                'semVer' => '1.0.0',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => '',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.2.3',
                'expectedMajor' => 1,
                'expectedMinor' => 2,
                'expectedPatch' => 3,
                'expectedPrerelease' => '',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '1.2.3',
            ],
            [
                'semVer' => '10.20.30',
                'expectedMajor' => 10,
                'expectedMinor' => 20,
                'expectedPatch' => 30,
                'expectedPrerelease' => '',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '10.20.30',
            ],
            [
                'semVer' => '1.1.2-prerelease+meta',
                'expectedMajor' => 1,
                'expectedMinor' => 1,
                'expectedPatch' => 2,
                'expectedPrerelease' => 'prerelease',
                'expectedBuild' => 'meta',
                'expectedReleaseVersion' => '1.1.2',
            ],
            [
                'semVer' => '1.1.2+meta',
                'expectedMajor' => 1,
                'expectedMinor' => 1,
                'expectedPatch' => 2,
                'expectedPrerelease' => '',
                'expectedBuild' => 'meta',
                'expectedReleaseVersion' => '1.1.2',
            ],
            [
                'semVer' => '1.1.2+meta-valid',
                'expectedMajor' => 1,
                'expectedMinor' => 1,
                'expectedPatch' => 2,
                'expectedPrerelease' => '',
                'expectedBuild' => 'meta-valid',
                'expectedReleaseVersion' => '1.1.2',
            ],
            [
                'semVer' => '1.0.0-alpha',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => 'alpha',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.0.0-beta',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => 'beta',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.0.0-alpha.beta',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => 'alpha.beta',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.0.0-alpha.beta.1',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => 'alpha.beta.1',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.0.0-alpha.1',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => 'alpha.1',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.0.0-0.3.7',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => '0.3.7',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.0.0-x.7.z.92',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => 'x.7.z.92',
                'expectedBuild' => '',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.0.0-alpha+beta',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => 'alpha',
                'expectedBuild' => 'beta',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.0.0+20130313144700',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => '',
                'expectedBuild' => '20130313144700',
                'expectedReleaseVersion' => '1.0.0',
            ],
            [
                'semVer' => '1.0.0-beta+exp.sha.5114f85',
                'expectedMajor' => 1,
                'expectedMinor' => 0,
                'expectedPatch' => 0,
                'expectedPrerelease' => 'beta',
                'expectedBuild' => 'exp.sha.5114f85',
                'expectedReleaseVersion' => '1.0.0',
            ],
        ];
    }

    #[DataProvider('provideInvalidSemVerStrings')]
    public function testThrowsExceptionIfIncorrectSemVerGiven(string $semVer): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Provided string is not a valid semantic version. Given value:/');
        new SemVer($semVer);
    }

    /**
     * @return array<string[]>
     */
    public static function provideInvalidSemVerStrings(): array
    {
        return [
            'missing minor and patch versions' => ['1'],
            'missing patch version' => ['1.0'],
            'too many version numbers' => ['1.2.3.4'],
            'empty pre-release label' => ['1.0.0-alpha..'],
            'empty pre-release label 2' => ['1.0.0-'],
            'empty build metadata' => ['1.0.0+'],
            'empty build metadata 2' => ['1.0.0-beta+exp..sha.5114f85'],
            'empty build metadata 3' => ['1.0.0-beta+exp.sha.'],
            'empty build metadata 4' => ['1.0.0-beta+..exp.sha.5114f85'],
            'empty build metadata 5' => ['1.0.0-beta+exp..sha.5114f85'],
            'empty build metadata 6' => ['1.0.0-beta+exp.sha..5114f85'],
            'empty build metadata 7' => ['1.0.0-beta+exp.sha.5114f85.'],
            'empty build metadata 8' => ['1.0.0-beta+exp.sha.5114f85..'],
            'non-numeric major, minor, patch versions' => ['a.b.c'],
            'empty pre-release label 3' => ['1.0.0-alpha.beta.1.'],
            'empty pre-release label 4' => ['1.0.0-alpha.1.'],
            'empty pre-release label 5' => ['1.0.0-0.3.7.'],
            'empty pre-release label 6' => ['1.0.0-x.7.z.92.'],
            'empty build metadata 9' => ['1.0.0-alpha+beta.'],
            'empty build metadata 10' => ['1.0.0+20130313144700.'],
            'empty build metadata 11' => ['1.0.0-beta+exp.sha.5114f85.'],
        ];
    }

    public function testAcceptsCorrectSemVerWithVPrefix1(): void
    {
        $valueObject = new SemVer('v1.0.0-x.7.z.92');
        self::assertSame('1.0.0-x.7.z.92', $valueObject->value);
    }

    public function testAcceptsCorrectSemVerWithVPrefix2(): void
    {
        $valueObject = new SemVer('V1.0.0-x.7.z.92');
        self::assertSame('1.0.0-x.7.z.92', $valueObject->value);
    }

    public function testEqualsToAnotherSemVer(): void
    {
        $valueObject = new SemVer('1.0.5');
        $sameValueObject = new SemVer('1.0.5');
        $notEqValueObject1 = new SemVer('1.0.1');
        $notEqValueObject2 = new SemVer('1.0.10');

        self::assertTrue($valueObject->equalTo($sameValueObject));
        self::assertFalse($valueObject->equalTo($notEqValueObject1));
        self::assertFalse($valueObject->equalTo($notEqValueObject2));
    }

    public function testNotEqualsToAnotherSemVer(): void
    {
        $valueObject = new SemVer('1.0.5');
        $sameValueObject = new SemVer('1.0.5');
        $notEqValueObject1 = new SemVer('1.0.1');
        $notEqValueObject2 = new SemVer('1.0.10');

        self::assertFalse($valueObject->notEqualTo($sameValueObject));
        self::assertTrue($valueObject->notEqualTo($notEqValueObject1));
        self::assertTrue($valueObject->notEqualTo($notEqValueObject2));
    }

    public function testLowerThanAnotherSemVer(): void
    {
        $valueObject = new SemVer('1.0.5');
        $sameValueObject = new SemVer('1.0.5');
        $lowerValueObject = new SemVer('1.0.1');
        $higherValueObject = new SemVer('1.0.10');

        self::assertTrue($valueObject->lowerThan($higherValueObject));
        self::assertFalse($valueObject->lowerThan($lowerValueObject));
        self::assertFalse($valueObject->lowerThan($sameValueObject));
    }

    public function testLowerThanOrEqualsToAnotherSemVer(): void
    {
        $valueObject = new SemVer('1.0.5');
        $sameValueObject = new SemVer('1.0.5');
        $lowerValueObject = new SemVer('1.0.1');
        $higherValueObject = new SemVer('1.0.10');

        self::assertTrue($valueObject->lowerThanOrEqualTo($higherValueObject));
        self::assertFalse($valueObject->lowerThanOrEqualTo($lowerValueObject));
        self::assertTrue($valueObject->lowerThanOrEqualTo($sameValueObject));
    }

    public function testGreaterThanAnotherSemVer(): void
    {
        $valueObject = new SemVer('1.0.5');
        $sameValueObject = new SemVer('1.0.5');
        $lowerValueObject = new SemVer('1.0.1');
        $higherValueObject = new SemVer('1.0.10');

        self::assertFalse($valueObject->greaterThan($higherValueObject));
        self::assertTrue($valueObject->greaterThan($lowerValueObject));
        self::assertFalse($valueObject->greaterThan($sameValueObject));
    }

    public function testGreaterThanOrEqualToAnotherSemVer(): void
    {
        $valueObject = new SemVer('1.0.5');
        $sameValueObject = new SemVer('1.0.5');
        $lowerValueObject = new SemVer('1.0.1');
        $higherValueObject = new SemVer('1.0.10');

        self::assertTrue($valueObject->greaterThanOrEqualTo($lowerValueObject));
        self::assertFalse($valueObject->greaterThanOrEqualTo($higherValueObject));
        self::assertTrue($valueObject->greaterThanOrEqualTo($sameValueObject));
    }

    public function testAcceptsCorrectSemVerAsStringable(): void
    {
        $semVer = '1.0.0';
        $valueObject = new SemVer(new StringableObject($semVer));
        self::assertSame($semVer, $valueObject->value);
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testPropertyIsAccessible(): void
    {
        $valueObject = new SemVer('1.13.2-prerelease+meta');
        self::assertSame('1.13.2-prerelease+meta', $valueObject->value);
        self::assertSame(1, $valueObject->major);
        self::assertSame(13, $valueObject->minor);
        self::assertSame(2, $valueObject->patch);
        self::assertSame('prerelease', $valueObject->preRelease);
        self::assertSame('meta', $valueObject->build);
        self::assertSame('1.13.2', $valueObject->releaseVersion);
    }

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new SemVer('1.0.0');
        self::assertSame('1.0.0', (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new SemVer('1.0.0');
        self::assertSame('1.0.0', $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        new SemVer(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new SemVer(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringableObjectGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new SemVer(new StringableObject(''));
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new SemVer('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(SemVer::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(SemVer::class);

        foreach (['validate', 'trimVPrefix', 'matchSemVer'] as $method) {
            self::assertTrue($reflection->getMethod($method)->isProtected());
        }
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new SemVer('v1.0.0');
        $valueObject2 = new SemVer('1.0.0');
        $valueObject3 = new SemVer('1.2.2');

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new SemVer('v1.0.0');
        $valueObject2 = new SemVer('1.0.0');
        $valueObject3 = new SemVer('1.2.2');

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
