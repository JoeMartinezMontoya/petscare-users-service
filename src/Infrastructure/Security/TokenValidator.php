<?php
namespace App\Infrastructure\Security;

use App\Domain\Security\TokenValidatorInterface;
use App\Infrastructure\Exception\ApiException;
use App\Infrastructure\Utils\HttpStatusCodes;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TokenValidator implements TokenValidatorInterface
{
    public function __construct(private readonly HttpClientInterface $httpClient, private readonly ParameterBagInterface $params)
    {}

    public function validate(Request $request): void
    {
        $authServiceUrl = $this->params->get('auth_service_url');

        $token = $this->extractToken($request->headers->get('Authorization'));

        try {
            $response = $this->httpClient->request('POST', (string) $authServiceUrl . '/public/api/validate-token', ['json' => ['token' => $token]]);

            if ($response->getStatusCode() !== 200) {
                throw new ApiException(
                    "https://api.petscare.com/errors/unauthorized",
                    "Unauthorized",
                    "Token could not be validate.",
                    HttpStatusCodes::UNAUTHORIZED
                );
            }

            $payload = $response->toArray();

            if (! isset($payload['email'])) {
                throw new ApiException(
                    "https://api.petscare.com/errors/unauthorized",
                    "Unauthorized",
                    "Missing claim.",
                    HttpStatusCodes::UNAUTHORIZED
                );
            }

            $request->attributes->set('email', $payload['email']);
        } catch (\Exception $e) {
            throw new ApiException(
                "https://api.petscare.com/errors/unauthorized",
                "Unauthorized",
                $e->getMessage(),
                HttpStatusCodes::UNAUTHORIZED
            );
        }
    }

    private function extractToken(?string $authHeader): string
    {
        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            throw new ApiException(
                "https://api.petscare.com/errors/unauthorized",
                "Unauthorized",
                "Missing or invalid Bearer Token.",
                HttpStatusCodes::UNAUTHORIZED
            );
        }

        return substr($authHeader, 7);
    }
}
