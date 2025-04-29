<?php
namespace App\Domain\Port\Service;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
