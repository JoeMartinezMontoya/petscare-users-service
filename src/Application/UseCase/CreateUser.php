<?php
namespace App\Application\UseCase;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObjects\BirthDate;
use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\FirstName;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\LastName;
use App\Domain\ValueObjects\UserName;
use App\Infrastructure\Exception\ApiException;
use App\Infrastructure\Service\UuidGenerator;
use App\Infrastructure\Utils\HttpStatusCodes;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CreateUser
{
    public function __construct(private UserRepositoryInterface $repository, private HttpClientInterface $httpClient, private ParameterBagInterface $params)
    {}

    public function execute(array $data): bool
    {
        if (! isset($data['email']) || ! isset($data['password'])) {
            throw new ApiException(
                'https://api.petscare.com/error/invalid-data',
                'Invalid Data',
                'User creation impossible without credentials.',
                HttpStatusCodes::BAD_REQUEST,
                null,
                '/public/api/registration'
            );
        }

        $uuid     = (new UuidGenerator)->generate();
        $authData = ['uuid' => $uuid, 'email' => $data['email'], 'password' => $data['password']];

        try {
            $authServiceUrl = $this->params->get('auth_service_url');

            $response = $this->httpClient->request(method: 'POST', url: (string) $authServiceUrl . '/public/api/registration', options: ['json' => json_encode($authData)]);
            $response = json_decode($response->getContent(), true);
        } catch (\Exception $e) {
            throw new ApiException(
                "https://api.petscare.com/errors/failed-registration",
                "Failed Registration",
                $e->getMessage(),
                HttpStatusCodes::BAD_REQUEST
            );
        }
        $user = new User(
            new Id($uuid),
            new UserName($data['userName']),
            new FirstName($data['firstName']),
            new LastName($data['lastName']),
            new BirthDate(new \DateTimeImmutable($data['birthDate'])),
            new CreatedAt(new \DateTimeImmutable())
        );

        return $this->repository->save($user);
    }
}
