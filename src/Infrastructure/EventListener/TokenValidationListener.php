<?php
namespace App\Infrastructure\EventListener;

use App\Domain\Port\Security\TokenValidatorInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TokenValidationListener
{
    public function __construct(private readonly TokenValidatorInterface $validator)
    {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path    = $request->getPathInfo();

        if (str_starts_with($path, '/public/')) {
            return;
        }

        $this->validator->validate($request);
    }
}
