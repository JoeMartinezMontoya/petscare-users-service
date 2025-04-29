<?php
namespace App\Tests\Infrastructure\Security;

use App\Domain\Port\Security\TokenValidatorInterface;
use App\Infrastructure\Exception\ApiException;
use App\Infrastructure\Utils\HttpStatusCodes;
use Symfony\Component\HttpFoundation\Request;

class FakeTokenValidator implements TokenValidatorInterface
{
    public function validate(Request $request): void
    {
        if (null === $request->headers->get('authorization')) {
            throw new ApiException(
                "https://api.petscare.com/errors/unauthorized",
                "Unauthorized",
                "Missing or invalid Bearer Token.",
                HttpStatusCodes::UNAUTHORIZED
            );
        }
    }
}
