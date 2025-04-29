<?php
namespace App\Domain\Port\Repository;

use App\Domain\Model\User as DomainUser;

interface UserRepositoryInterface
{
    public function save(DomainUser $user): bool;
}
