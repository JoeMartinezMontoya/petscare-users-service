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
use App\Infrastructure\Service\UuidGenerator;

class CreateUser
{
    public function __construct(private UserRepositoryInterface $repository)
    {}

    public function execute(array $data): bool
    {
        // Générer l'UUID, récupérer mail et password et l'envoyer au Auth service
        $uuid = (new UuidGenerator)->generate();

        // Une fois la réponse de auth recu on continue ici
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
