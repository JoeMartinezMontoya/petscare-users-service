<?php
namespace App\Application\UseCase;

use App\Domain\Model\User;
use App\Domain\Port\Client\AuthServiceClientInterface;
use App\Domain\Port\Repository\UserRepositoryInterface;
use App\Domain\ValueObjects\BirthDate;
use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\FirstName;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\LastName;
use App\Domain\ValueObjects\UserName;
use App\Infrastructure\Exception\ApiException;
use App\Infrastructure\Service\UuidGenerator;
use App\Infrastructure\Utils\HttpStatusCodes;

class CreateUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private AuthServiceClientInterface $authServiceClient
    ) {}

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

        $uuid = (new UuidGenerator)->generate();
        if (! $this->authServiceClient->createUser($uuid, $data['email'], $data['password'])) {
            throw new ApiException(
                "https://api.petscare.com/errors/failed-registration",
                "Failed Registration",
                "Could not create user on auth-service.",
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
