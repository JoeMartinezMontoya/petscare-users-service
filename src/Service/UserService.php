<?php
namespace App\Service;

use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\UserRepository;
use App\Utils\HttpStatusCodes;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function createUser(array $data): array
    {
        if ($this->userExists($data['email'])) {
            return $this->buildErrorResponse(
                "UserService::createUser",
                "Inscription annulée",
                "L'adresse email est déjà associée à un utilisateur",
                HttpStatusCodes::CONFLICT
            );
        }

        $birthdate = \DateTimeImmutable::createFromFormat('Y-m-d', $data['birthdate']);
        if (! $birthdate) {
            throw new ApiException(
                "Invalid birthdate format",
                "La date de naissance fournie est invalide.",
                "Format attendu : YYYY-MM-DD",
                HttpStatusCodes::BAD_REQUEST
            );
        }

        $user = (new User())
            ->setEmail($data['email'])
            ->setPassword($this->passwordHasher->hashPassword(new User(), $data['password']))
            ->setUserName($data['username'])
            ->setFirstName($data['firstname'])
            ->setLastName($data['lastname'])
            ->setBirthDate($birthdate)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setRoles(['ROLE_USER']);

        $this->userRepository->persistUser($user);

        return $this->buildSuccessResponse(
            "UserService::createUser",
            "Inscription effectuée",
            "Votre compte a été créé avec succès",
            HttpStatusCodes::CREATED
        );
    }

    public function checkUserCredentials(array $data): array
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);

        if ($user && $this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return array_merge(
                $this->buildSuccessResponse(
                    "UserService::checkUserCredentials",
                    "Connexion acceptée",
                    "Identifiants valides",
                    HttpStatusCodes::SUCCESS
                ),
                ["email" => $user->getEmail()]
            );
        }

        return $this->buildErrorResponse(
            "UserService::checkUserCredentials",
            "Connexion impossible",
            "Identifiants invalides",
            HttpStatusCodes::UNAUTHORIZED
        );
    }

    public function userExists(string $email): bool
    {
        return $this->userRepository->findOneBy(['email' => $email]) !== null;
    }

    private function buildSuccessResponse(string $source, string $title, string $detail, int $status): array
    {
        return [
            "source"  => $source,
            "type"    => "https://example.com/probs/success",
            "title"   => $title,
            "status"  => $status,
            "detail"  => $detail,
            "message" => "Opération réussie",
        ];
    }

    private function buildErrorResponse(string $source, string $title, string $detail, int $status): array
    {
        return [
            "source"  => $source,
            "type"    => "https://example.com/probs/error",
            "title"   => $title,
            "status"  => $status,
            "detail"  => $detail,
            "message" => "Erreur rencontrée",
        ];
    }
}
