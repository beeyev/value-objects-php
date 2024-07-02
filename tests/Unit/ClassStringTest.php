<?php

declare(strict_types=1);

namespace Beeyev\ValueObject\Tests\Unit;

use Beeyev\ValueObject\ClassString;
use Beeyev\ValueObject\Exceptions\ValueObjectInvalidArgumentException;
use Beeyev\ValueObject\Tests\AbstractTestCase;
use Beeyev\ValueObject\Tests\Dummy\StringableObject;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(ClassString::class)]
final class ClassStringTest extends AbstractTestCase
{
    public function testAcceptsCorrectClassStringAsString(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        self::assertSame(\DateTimeImmutable::class, $valueObject->value);
    }

    public function throwsExceptionIfClassStringStartingWithDigitProvided(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Class string value cannot start with a digit. Given value:/');
        new ClassString('1Class');
    }

    public function testAcceptsCorrectClassStringAsStringableObject(): void
    {
        $valueObject = new ClassString(new StringableObject(\DateTimeImmutable::class));
        self::assertSame(\DateTimeImmutable::class, $valueObject->value);
    }

    public function testChecksIfClassExists(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        self::assertTrue($valueObject->isClassExist());
    }

    public function testChecksIfClassDoesNotExist(): void
    {
        $valueObject = new ClassString('NonExistentClass');
        self::assertFalse($valueObject->isClassExist());
    }

    public function testChecksIfInterfaceExists(): void
    {
        $valueObject = new ClassString(\DateTimeInterface::class);
        self::assertTrue($valueObject->isInterfaceExist());
    }

    public function testChecksIfInterfaceDoesNotExist(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        self::assertFalse($valueObject->isInterfaceExist());
    }

    public function testChecksIfInstanceOfAllowsOnlyExpectedTypes(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type object|string/');
        $valueObject->isInstanceOf(123); // @phpstan-ignore argument.type
    }

    public function testChecksIfInstanceOfAnotherObject(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        self::assertTrue($valueObject->isInstanceOf(new \DateTimeImmutable()));
    }

    public function testChecksIfIsNotInstanceOfInterface(): void
    {
        $valueObject = new ClassString(\DateTime::class);
        self::assertTrue($valueObject->isInstanceOf(\DateTimeInterface::class));
    }

    public function testChecksIfInstanceOfAnotherClassString(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        self::assertTrue($valueObject->isInstanceOf(\DateTimeInterface::class));
    }

    public function testChecksIfIsNotInstanceOfAnotherObject(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        self::assertFalse($valueObject->isInstanceOf(new \DateTime()));
    }

    public function testCheckInstanceOfAnotherClassStringVolumeObject(): void
    {
        $dateTimeInterfaceVO = new ClassString(\DateTimeImmutable::class);
        $dateTimeImmutableVO = new ClassString(\DateTimeInterface::class);
        self::assertTrue($dateTimeInterfaceVO->isInstanceOf($dateTimeImmutableVO->value));
    }

    public function testClassCanBeInstantiated(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        $instance = $valueObject->instantiate();
        self::assertInstanceOf(\DateTimeImmutable::class, $instance);
    }

    public function testClassFailsToBeInstantiated(): void
    {
        $expectedErrorThrown = false;
        $valueObject = new ClassString('BlahBlah');

        try {
            $valueObject->instantiate();
        } catch (\Error $error) {
            $expectedErrorThrown = true;
        }

        self::assertTrue($expectedErrorThrown, 'Expected error was not thrown.');
    }

    public function testClassCanBeInstantiatedWithParameters(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        $instance = $valueObject->instantiateWith('2021-01-01 00:00:00', new \DateTimeZone('UTC'));

        $dateTimeExpected = new \DateTimeImmutable('2021-01-01 00:00:00', new \DateTimeZone('UTC'));
        self::assertInstanceOf(\DateTimeImmutable::class, $instance);
        self::assertEquals($dateTimeExpected, $instance);
    }

    public function testFailsWithIncorrectClassString(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Class string value cannot contain spaces.');
        new ClassString('bla bla');
    }

    public function testFailsWithEmptyClassString(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new ClassString('');
    }

    // @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //  @@ //

    public function testThatValueObjectIsStringable(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        self::assertSame(\DateTimeImmutable::class, (string) $valueObject);
    }

    public function testToStringMethodWorksCorrect(): void
    {
        $valueObject = new ClassString(\DateTimeImmutable::class);
        self::assertSame(\DateTimeImmutable::class, $valueObject->toString());
    }

    // ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //  ## //

    public function testThrowsExceptionIfNullGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be of type Stringable|string/');
        new ClassString(null); // @phpstan-ignore argument.type
    }

    public function testThrowsExceptionIfNoArgumentsGiven(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/^Too few arguments to function/');

        new ClassString(); // @phpstan-ignore arguments.count
    }

    public function testThrowsExceptionIfEmptyStringableObjectGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new ClassString(new StringableObject(''));
    }

    public function testThrowsExceptionIfEmptyStringGiven(): void
    {
        $this->expectException(ValueObjectInvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');
        new ClassString('');
    }

    public function testIfClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(ClassString::class);

        self::assertTrue($reflection->isReadOnly());
        self::assertFalse($reflection->isFinal());
    }

    public function testIfPropertiesAndMethodsAreProtected(): void
    {
        $reflection = new \ReflectionClass(ClassString::class);

        self::assertTrue($reflection->getMethod('validate')->isProtected());
    }

    public function testSameAsAnotherObject(): void
    {
        $valueObject1 = new ClassString('test');
        $valueObject2 = new ClassString('test');
        $valueObject3 = new ClassString('different');

        self::assertTrue($valueObject1->sameAs($valueObject2));
        self::assertFalse($valueObject1->sameAs($valueObject3));
    }

    public function testNotSameAsAnotherObject(): void
    {
        $valueObject1 = new ClassString('test');
        $valueObject2 = new ClassString('test');
        $valueObject3 = new ClassString('different');

        self::assertFalse($valueObject1->notSameAs($valueObject2));
        self::assertTrue($valueObject1->notSameAs($valueObject3));
    }
}
