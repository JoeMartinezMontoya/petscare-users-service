<?php
namespace App\Domain\Repository;

use App\Domain\Model\User as DomainUser;

interface UserRepositoryInterface
{
    public function save(DomainUser $user): bool;
}
