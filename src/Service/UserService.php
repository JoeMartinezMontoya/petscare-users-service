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

    public function createUser(array $data): string | null
    {
        if (null !== $this->userExists($data['email'])) {
            throw new ApiException(
                "Registration canceled",
                "Email already used",
                "Please input another email adress",
                HttpStatusCodes::CONFLICT
            );
        }

        $birthdate = \DateTimeImmutable::createFromFormat('Y-m-d', $data['birthdate']);
        if (! $birthdate) {
            throw new ApiException(
                "Registration canceled",
                "Wrong birthdate format",
                "Please input a correct birthdate format",
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

        return $user->getUserName() ?? null;
    }

    public function checkUserCredentials(array $data): array
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);

        if ($user && $this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return [
                "source"  => "UserService::checkUserCredentials",
                "type"    => "https://example.com/probs/success",
                "title"   => "Connexion réussie",
                "status"  => HttpStatusCodes::SUCCESS,
                "detail"  => "Identifiants valides.",
                "message" => "Connexion acceptée.",
                "email"   => $user->getEmail(),
            ];
        }

        throw new ApiException(
            "Connexion impossible",
            "Identifiants invalides.",
            "Vérifiez vos informations de connexion.",
            HttpStatusCodes::UNAUTHORIZED
        );
    }

    public function userExists(string $email): object | null
    {
        return $this->userRepository->findOneBy(['email' => $email]) ?? null;
    }
}
