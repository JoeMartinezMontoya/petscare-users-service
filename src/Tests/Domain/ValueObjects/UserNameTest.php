<?php
namespace App\Tests\Domain\ValueObjects;

use App\Domain\Exception\DomainRuleViolationException;
use App\Domain\ValueObjects\UserName;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('vo')]
final class UserNameTest extends TestCase
{
    public function testMustNotBeEmpty(): void
    {
        $this->expectException(DomainRuleViolationException::class);
        new UserName('');
    }

    public function testCanNotContainMoreThanTwentyCharacters(): void
    {
        $lorem = 'Lorem ipsum dolor sit amet consectetur adipisicing elit Possimus ab exercitationem harum iure saepe velit molestias quos cupiditate reprehenderit doloremque voluptatem quasi minima assumenda dolorum eligendi voluptatum excepturi laudantium praesentium';
        $this->expectException(DomainRuleViolationException::class);
        new UserName($lorem);
    }

    public function testCanBeCastToString(): void
    {
        $username = new UserName('Test username');
        $this->assertSame('Test username', (string) $username);
    }

    public function testGetsEqualityWithEqualsMethod(): void
    {
        $username1 = new UserName('Test username');
        $username2 = new UserName('Test username');
        $this->assertTrue($username1->equals($username2));
    }
}
