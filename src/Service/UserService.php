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
        if (! $this->userExists($data['email']) instanceof User) {
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

    public function checkUserCredentials(array $data)
    {
        $user = $this->userExists($data['email']);

        if (! $user || ! $this->passwordHasher->isPasswordValid($user, $data['password'])) {
            throw new ApiException(
                "Invalid Credentials",
                "Invalid Credentials",
                "The provided email or password is incorrect",
                HttpStatusCodes::UNAUTHORIZED
            );
        }

        return $user->getEmail();
    }

    public function userExists(string $email): User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (! $user instanceof User) {
            throw new ApiException(
                "User Not Found",
                "User Not Found",
                "No user found associated to $email",
                HttpStatusCodes::NOT_FOUND
            );
        }
        return $user;
    }
}
