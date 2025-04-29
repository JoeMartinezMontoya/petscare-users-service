<?php
namespace App\Tests\Infrastructure\Controller\Trait;

use App\Domain\Port\Security\TokenValidatorInterface;
use App\Tests\Infrastructure\Security\FakeTokenValidator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait SetUpTrait
{
    protected KernelBrowser $client;

    protected function setUpClientAndToken(): void
    {
        $this->client = static::createClient();

        static::getContainer()->set(
            TokenValidatorInterface::class,
            new FakeTokenValidator()
        );
    }
}
