<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TokenValidationListener
{
    private HttpClientInterface $httpClient;
    private LoggerInterface $loggerInterface;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $loggerInterface)
    {
        $this->httpClient      = $httpClient;
        $this->loggerInterface = $loggerInterface;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Liste des routes à ignorer
        $excludedPaths = [
            '/api/auth/login-user', // Route de connexion
            '/api/auth/register-user', // Route d'inscription si applicable
            '/api/users/create-user',
            '/api/users/check-user',
            '/api/users/check-user-credentials',
        ];

        // Si la route est exclue, on ne fait rien
        foreach ($excludedPaths as $path) {
            if ($request->getPathInfo() === $path) {
                return;
            }
        }

        // Vérifie le header Authorization
        $authHeader = $request->headers->get('Authorization');
        $this->loggerInterface->info("Authorization Header: " . $authHeader);

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new UnauthorizedHttpException('Bearer', 'Token manquant ou invalide.');
        }

        try {
            // Envoie le token pour validation
            $token    = str_replace('Bearer ', '', $authHeader);
            $response = $this->httpClient->request('POST', $_ENV['AUTH_SERVICE_BASE_URL'] . '/api/validate-token', [
                'json' => ['token' => $token],
            ]);

            // Si la validation échoue, renvoie une erreur
            if ($response->getStatusCode() !== 200) {
                throw new UnauthorizedHttpException('Bearer', 'Token invalide.');
            }

            // Stocke le payload dans les attributs de la requête
            $payload = $response->toArray();
            $this->loggerInterface->info("Payload: " . print_r($payload, true)); // Log du payload

            // Avant de mettre le dd, je logge ici
            $this->loggerInterface->info("Le payload est maintenant dans la requête");

            $request->attributes->set('email', $payload['email']);
        } catch (\Exception $e) {
            // Log des erreurs
            $this->loggerInterface->error("Erreur lors de la validation du token: " . $e->getMessage());
            throw new UnauthorizedHttpException('Bearer', 'Erreur lors de la validation du token.');
        }
    }
}
