<?php
namespace App\Tests\Domain\ValueObjects;

use App\Domain\ValueObjects\CreatedAt;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('vo')]
final class CreatedAtTest extends TestCase
{
    public function testCanBeCastToString(): void
    {
        $createdAt = new CreatedAt(new \DateTimeImmutable('now'));
        $this->assertSame((new \DateTimeImmutable('now'))->format('Y-m-d'), (string) $createdAt);
    }

    public function testGetsEqualityWithEqualsMethod(): void
    {
        $now        = new \DateTimeImmutable('now');
        $createdAt1 = new CreatedAt($now);
        $createdAt2 = new CreatedAt($now);
        $this->assertTrue($createdAt1->equals($createdAt2));
    }
}
