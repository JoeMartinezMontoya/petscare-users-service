<?php
namespace App\Infrastructure\Client;

use App\Domain\Port\Client\AuthServiceClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthServiceClient implements AuthServiceClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private ParameterBagInterface $params
    ) {}

    public function createUser(string $uuid, string $email, string $password): bool
    {
        $authServiceUrl = $this->params->get('auth_service_url');

        $response = $this->httpClient->request('POST', $authServiceUrl . '/public/api/registration', [
            'json' => [
                'uuid'     => $uuid,
                'email'    => $email,
                'password' => $password,
            ],
        ]);

        $content = json_decode($response->getContent(), true);

        return $content['data'][0];
    }
}
