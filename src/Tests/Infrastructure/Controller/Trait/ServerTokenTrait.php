<?php
namespace App\Tests\Infrastructure\Controller\Trait;

trait ServerTokenTrait
{
    protected function getToken(): array
    {
        return ['HTTP_Authorization' => 'Bearer fake.jwt.token', 'CONTENT_TYPE' => 'application/json'];
    }
}
