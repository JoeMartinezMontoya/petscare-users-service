<?php
namespace App\Tests\Application\UseCase;

use App\Application\UseCase\CreateUser;
use App\Domain\Repository\UserRepositoryInterface;
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

        $useCase = new CreateUser($repository);

        $data = [
            'userName'  => 'EddieVH',
            'lastName'  => 'Van Halen',
            'firstName' => 'Eddie',
            'birthDate' => '1993-11-05',
        ];

        $result = $useCase->execute($data);
        $this->assertTrue($result);
    }
}
