<?php
namespace App\Tests\Domain\ValueObjects;

use App\Domain\Exception\DomainRuleViolationException;
use App\Domain\ValueObjects\LastName;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('vo')]
final class LastNameTest extends TestCase
{
    public function testMustNotBeEmpty(): void
    {
        $this->expectException(DomainRuleViolationException::class);
        new LastName('');
    }

    public function testMustReturnAnErrorIfContainNumbers()
    {
        $this->expectException(DomainRuleViolationException::class);
        new LastName('Van Halen2');
    }

    public function testMustReturnAnErrorIfContainSpecialCharacters()
    {
        $this->expectException(DomainRuleViolationException::class);
        new LastName('Van Halen#');
    }

    public function testCanBeCastToString(): void
    {
        $lastname = new LastName('Test lastname');
        $this->assertSame('Test lastname', (string) $lastname);
    }

    public function testGetsEqualityWithEqualsMethod(): void
    {
        $lastname1 = new LastName('Test lastname');
        $lastname2 = new LastName('Test lastname');
        $this->assertTrue($lastname1->equals($lastname2));
    }
}
