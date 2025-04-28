<?php
namespace App\Tests\Application\UseCase;

use App\Application\UseCase\CreateUser;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Exception\ApiException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Group('unit')]
#[Group('usecase')]
final class CreateUserTest extends TestCase
{
    public function testMustReturnABoolean(): void
    {
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->expects($this->once())->method('save')->willReturn(true);

        $httpClient = $this->createMock(HttpClientInterface::class);

        $params = $this->createMock(ParameterBagInterface::class);
        $params->expects($this->once())->method('get')->willReturn('http://auth-service');

        $useCase = new CreateUser($repository, $httpClient, $params);

        $data = [
            'userName'  => 'EddieVH',
            'lastName'  => 'Van Halen',
            'firstName' => 'Eddie',
            'birthDate' => '1993-11-05',
            'email'     => 'admin@test.fr',
            'password'  => 'testpassword',
        ];

        $result = $useCase->execute($data);
        $this->assertTrue($result);
    }

    public function testMustReturnAnErrorIfInvalidDataProvided()
    {

        $repository = $this->createMock(UserRepositoryInterface::class);
        $httpClient = $this->createMock(HttpClientInterface::class);
        $params     = $this->createMock(ParameterBagInterface::class);

        $useCase = new CreateUser($repository, $httpClient, $params);

        $data = [
            'userName'  => 'EddieVH',
            'lastName'  => 'Van Halen',
            'firstName' => 'Eddie',
            'birthDate' => '1993-11-05',
        ];

        $this->expectException(ApiException::class);
        $useCase->execute($data);
    }
}
