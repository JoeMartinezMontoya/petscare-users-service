<?php
namespace App\Tests\Infrastructure\Controller;

use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[Group('functional')]
#[Group('controller')]
final class CreateUserControllerTest extends WebTestCase
{
    private $client;
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }
    public function testMustReturnAValidResponse(): void
    {
        $data = [
            'userName'  => 'EddieVH',
            'lastName'  => 'Van Halen',
            'firstName' => 'Eddie',
            'birthDate' => '1993-11-05',
            'email'     => 'test@petscare.com',
            'password'  => 'testpassword',
        ];

        $this->client->request(method: 'POST', uri: '/public/api/registration', content: json_encode($data));
        $this->assertResponseStatusCodeSame(201);
    }
    public function testMustReturnAnErrorIfInvalidDataProvided(): void
    {
        $this->client->request(method: 'POST', uri: '/public/api/registration', content: json_encode([]));
        $this->assertResponseStatusCodeSame(400);
    }
}
