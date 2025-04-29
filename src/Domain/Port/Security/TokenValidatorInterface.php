<?php
namespace App\Domain\Port\Security;

use Symfony\Component\HttpFoundation\Request;

interface TokenValidatorInterface
{
    public function validate(Request $request): void;
}
