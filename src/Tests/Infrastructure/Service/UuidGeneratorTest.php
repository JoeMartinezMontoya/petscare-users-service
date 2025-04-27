<?php
namespace App\Tests\Infrastructure\Service;

use App\Infrastructure\Service\UuidGenerator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('service')]
final class UuidGeneratorTest extends TestCase
{
    public function testMustReturnAValidUuid(): void
    {
        $this->assertTrue((bool) preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/", (new UuidGenerator)->generate()));
    }
}
