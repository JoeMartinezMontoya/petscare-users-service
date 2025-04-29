<?php
namespace App\Domain\Port\Repository;

use App\Domain\Model\UserProfile as DomainUser;

interface UserRepositoryInterface
{
    public function save(DomainUser $user): bool;
}
