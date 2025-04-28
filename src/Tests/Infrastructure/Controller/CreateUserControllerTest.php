<?php
namespace App\Tests\Infrastructure\Controller;

use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[Group('functional')]
#[Group('controller')]
final class CreateUserControllerTest extends WebTestCase
{
    public function testMustReturnAValidResponse(): void
    {

    }
}
