<?php
namespace App\Tests\Domain\ValueObjects;

use App\Domain\ValueObjects\BirthDate;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('vo')]
final class BirthDateTest extends TestCase
{
    public function testCanBeCastToString(): void
    {
        $birthDate = new BirthDate(new \DateTimeImmutable('now'));
        $this->assertSame((new \DateTimeImmutable('now'))->format('Y-m-d'), (string) $birthDate);
    }

    public function testGetsEqualityWithEqualsMethod(): void
    {
        $now        = new \DateTimeImmutable('now');
        $birthDate1 = new BirthDate($now);
        $birthDate2 = new BirthDate($now);
        $this->assertTrue($birthDate1->equals($birthDate2));
    }
}
