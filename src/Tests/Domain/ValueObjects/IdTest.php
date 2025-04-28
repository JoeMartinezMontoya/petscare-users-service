<?php
namespace App\Tests\Domain\ValueObjects;

use App\Domain\Exception\DomainRuleViolationException;
use App\Domain\ValueObjects\Id;
use App\Infrastructure\Service\UuidGenerator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('vo')]
final class IdTest extends TestCase
{
    public function testMustNotBeEmpty(): void
    {
        $this->expectException(DomainRuleViolationException::class);
        new Id('');
    }

    public function testMustRespectUuidFormat(): void
    {
        $id = new Id((new UuidGenerator)->generate());

        $pattern = "/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";
        $this->assertMatchesRegularExpression($pattern, $id->getValue());
    }

    public function testMustReturnsAnExceptionInItDoesNotRespectUuuidFormat(): void
    {
        $this->expectException(DomainRuleViolationException::class);
        new Id('test');
    }

    public function testCanBeCastToString(): void
    {
        $Uuid = (new UuidGenerator)->generate();
        $id   = new Id($Uuid);
        $this->assertEquals($Uuid, (string) $id);
    }

    public function testGetsEqualityWithEqualsMethod(): void
    {
        $Uuid = (new UuidGenerator)->generate();
        $id1  = new Id($Uuid);
        $id2  = new Id($Uuid);
        $this->assertTrue($id1->equals($id2));
    }
}
