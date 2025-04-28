<?php
namespace App\Tests\Infrastructure\Mapper;

use App\Domain\Model\User as DomainUser;
use App\Domain\ValueObjects\BirthDate;
use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\FirstName;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\LastName;
use App\Domain\ValueObjects\UserName;
use App\Infrastructure\Entity\User as InfrastructureUser;
use App\Infrastructure\Mapper\UserMapper;
use App\Infrastructure\Service\UuidGenerator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('mapper')]
final class UserMapperTest extends TestCase
{
    public function testMustReturnAnInfrastructureUser(): void
    {
        $domainUser = new DomainUser(
            new Id((new UuidGenerator)->generate()),
            new UserName('Admin'),
            new FirstName('Eddie'),
            new LastName('Van Halen'),
            new BirthDate(new \DateTimeImmutable('1993-11-05')),
            new CreatedAt(new \DateTimeImmutable())
        );
        $result = (new UserMapper())->mapToInfrastructure($domainUser);
        $this->assertInstanceOf(InfrastructureUser::class, $result);
    }
    public function testMustReturnADomainUser(): void
    {
        $infrastructureUser = (new InfrastructureUser())->setId((new UuidGenerator)->generate())
            ->setUserName('Admin')
            ->setFirstName('Eddie')
            ->setLastName('Van Halen')
            ->setBirthDate(new \DateTimeImmutable('1993-11-05'))
            ->setCreatedAt(new \DateTimeImmutable());

        $result = (new UserMapper())->mapToDomain($infrastructureUser);
        $this->assertInstanceOf(DomainUser::class, $result);
    }
}
