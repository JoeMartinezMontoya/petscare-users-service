<?php
namespace App\Tests\Application\UseCase;

use App\Application\UseCase\CreateUser;
use App\Domain\Port\Client\AuthServiceClientInterface;
use App\Domain\Port\Repository\UserRepositoryInterface;
use App\Infrastructure\Exception\ApiException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('usecase')]
final class CreateUserTest extends TestCase
{
    public function testMustReturnABoolean(): void
    {
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->expects($this->once())->method('save')->willReturn(true);

        $authServiceInterface = $this->createMock(AuthServiceClientInterface::class);
        $authServiceInterface->expects($this->once())->method('createUser')->willReturn(true);

        $useCase = new CreateUser($repository, $authServiceInterface);

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

        $repository           = $this->createMock(UserRepositoryInterface::class);
        $authServiceInterface = $this->createMock(AuthServiceClientInterface::class);

        $useCase = new CreateUser($repository, $authServiceInterface);

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
