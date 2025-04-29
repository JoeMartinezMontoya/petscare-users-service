<?php
namespace App\Infrastructure\Service;

use App\Domain\Port\Service\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {return Uuid::v4()->toRfc4122();}
}
