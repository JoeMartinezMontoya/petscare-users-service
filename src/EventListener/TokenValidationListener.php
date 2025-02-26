<?php
namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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

        $excludedPaths = [
            '/api/auth/login-user',
            '/api/auth/register-user',
            '/api/users/check-user-credentials',
        ];

        if (in_array($request->getPathInfo(), $excludedPaths, true)) {
            return;
        }

        $token = $this->extractToken($request->headers->get('Authorization'));

        try {
            $response = $this->httpClient->request('POST', $this->authServiceUrl . '/api/validate-token', [
                'json' => ['token' => $token],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new UnauthorizedHttpException('Bearer', 'Token invalide.');
            }

            $payload = $response->toArray();

            if (! isset($payload['email'])) {
                throw new UnauthorizedHttpException('Bearer', 'Réponse invalide du service d’authentification.');
            }

            $request->attributes->set('email', $payload['email']);
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Bearer', 'Erreur lors de la validation du token.');
        }
    }

    private function extractToken(?string $authHeader): string
    {
        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            throw new UnauthorizedHttpException('Bearer', 'Token manquant ou invalide.');
        }

        return substr($authHeader, 7);
    }
}
