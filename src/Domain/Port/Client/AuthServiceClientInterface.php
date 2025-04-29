<?php
namespace App\Domain\Port\Client;

interface AuthServiceClientInterface
{
    public function createUser(string $uuid, string $email, string $password): bool;
}
