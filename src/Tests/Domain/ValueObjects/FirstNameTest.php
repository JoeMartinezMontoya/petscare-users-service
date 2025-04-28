<?php
namespace App\Tests\Domain\ValueObjects;

use App\Domain\Exception\DomainRuleViolationException;
use App\Domain\ValueObjects\FirstName;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('vo')]
final class FirstNameTest extends TestCase
{
    public function testMustNotBeEmpty(): void
    {
        $this->expectException(DomainRuleViolationException::class);
        new FirstName('');
    }

    public function testMustReturnAnErrorIfContainNumbers()
    {
        $this->expectException(DomainRuleViolationException::class);
        new FirstName('Jean-Marie2');
    }

    public function testMustReturnAnErrorIfContainSpecialCharacters()
    {
        $this->expectException(DomainRuleViolationException::class);
        new FirstName('Jean-Marie#');
    }

    public function testCanBeCastToString(): void
    {
        $firstName = new FirstName('Test firstName');
        $this->assertSame('Test firstName', (string) $firstName);
    }

    public function testGetsEqualityWithEqualsMethod(): void
    {
        $firstName1 = new FirstName('Test firstName');
        $firstName2 = new FirstName('Test firstName');
        $this->assertTrue($firstName1->equals($firstName2));
    }
}
