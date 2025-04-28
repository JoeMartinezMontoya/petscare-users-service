<?php
namespace App\Infrastructure\Repository;

use App\Domain\Model\User as DomainUser;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Entity\User as InfrastructureUser;
use App\Infrastructure\Mapper\UserMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfrastructureUser>
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(private UserMapper $mapper, ManagerRegistry $registry)
    {
        parent::__construct($registry, InfrastructureUser::class);
    }

    public function save(DomainUser $user): bool
    {
        $infrastructureUser = $this->mapper->mapToInfrastructure($user);
        try {
            $this->getEntityManager()->persist($infrastructureUser);
            $this->getEntityManager()->flush();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
