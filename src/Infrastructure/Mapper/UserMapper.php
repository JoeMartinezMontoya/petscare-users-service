<?php
namespace App\Infrastructure\Mapper;

use App\Domain\Model\UserProfile as DomainUser;
use App\Domain\ValueObjects\BirthDate;
use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\FirstName;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\LastName;
use App\Domain\ValueObjects\UserName;
use App\Infrastructure\Entity\UserProfile as InfrastructureUser;

class UserMapper
{
    public function mapToInfrastructure(DomainUser $user): InfrastructureUser
    {
        return (new InfrastructureUser)->setId($user->getId()->getValue())
            ->setUserName($user->getUserName()->getValue())
            ->setFirstName($user->getFirstName()->getValue())
            ->setLastName($user->getLastName()->getValue())
            ->setBirthDate($user->getBirthDate()->getValue())
            ->setCreatedAt($user->getCreatedAt()->getValue());
    }

    public function mapToDomain(InfrastructureUser $user): DomainUser
    {
        return new DomainUser(
            new Id($user->getId()),
            new UserName($user->getUserName()),
            new FirstName($user->getFirstName()),
            new LastName($user->getLastName()),
            new BirthDate($user->getBirthDate()),
            new CreatedAt($user->getCreatedAt())
        );
    }
}
