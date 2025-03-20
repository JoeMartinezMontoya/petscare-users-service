<?php
namespace App\EventListener;

use App\Exception\ApiException;
use App\Utils\HttpStatusCodes;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TokenValidationListener
{
    private HttpClientInterface $httpClient;
    private string $authServiceUrl;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $params)
    {
        $this->httpClient     = $httpClient;
        $this->authServiceUrl = $params->get('auth_service_url');
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path    = $request->getPathInfo();

        if (str_starts_with($path, '/public/')) {
            return;
        }

        $token = $this->extractToken($request->headers->get('Authorization'));

        try {
            $response = $this->httpClient->request('POST', $this->authServiceUrl . '/public/api/validate-token', [
                'json' => ['token' => $token],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new ApiException(
                    "Invalid Token",
                    "Invalid bearer token",
                    "Invalid bearer token",
                    HttpStatusCodes::UNAUTHORIZED
                );
            }

            $payload = $response->toArray();

            if (! isset($payload['email'])) {
                throw new ApiException(
                    "Invalid Token",
                    "Invalid response",
                    "Invalid authentication respons, email adress missing",
                    HttpStatusCodes::UNAUTHORIZED
                );
            }

            $request->attributes->set('email', $payload['email']);
        } catch (\Exception $e) {
            throw new ApiException(
                "Validation Impossible",
                "Validation impossible",
                $e->getMessage(),
                HttpStatusCodes::UNAUTHORIZED
            );
        }
    }

    private function extractToken(?string $authHeader): string
    {
        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            throw new ApiException(
                "Missing Token",
                "Missing bearer token",
                "Missing bearer token",
                HttpStatusCodes::UNAUTHORIZED
            );
        }

        return substr($authHeader, 7);
    }
}
