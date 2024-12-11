<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use App\Entity\User;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function checkIfUserExists(string $email): bool
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        return gettype($user) === "object";
    }

    public function createUser(array $data)
    {
        if (!$this->checkIfUserExists($data['email'])) {
            $user = new User();
            $birthdate = \DateTimeImmutable::createFromFormat('Y-m-d', $data['birthdate']);
            if (!$birthdate) {
                throw new \InvalidArgumentException('La date de naissance fournie est invalide.');
            }
            $user->setEmail($data['email'])
                ->setPassword($this->userPasswordHasherInterface->hashPassword($user, $data['password']))
                ->setUserName($data['username'])
                ->setFirstName($data['firstname'])
                ->setLastName($data['lastname'])
                ->setBirthDate($birthdate)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setRoles(['ROLE_USER']);

            $this->userRepository->persistUser($user);

            return [
                "source" => 'UserService::createUser',
                "type" => "https://example.com/probs/invalid-data",
                "title" => "Inscription effectuée",
                "status" => Response::HTTP_CREATED,
                "detail" => "Votre compte a été créer avec succès",
                "message" => "Tudu bon"
            ];
        }

        return [
            "source" => 'UserService::createUser',
            "type" => "https://example.com/probs/invalid-data",
            "title" => "Inscription annulée",
            "status" => Response::HTTP_CONFLICT,
            "detail" => "L'adresse email est déjà associé à un utilisateur",
            "message" => "Tudu pas bon"
        ];
    }

    public function checkUserCredentials(string $email, string $password)
    {
        $user = $this->findUserByEmail($email);

        if ($user && $this->verifyPassword($user, $password)) {
            return [
                'success' => true,
                'message' => 'Identifiants correct',
                'source' => 'UserService::checkUserCredentials',
                'code' => 200
            ];
        }

        return [
            'success' => false,
            'message' => 'Identifiants incorrect',
            'source' => 'UserService::checkUserCredentials',
            'code' => 409
        ];
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function verifyPassword(User $user, string $password): bool
    {
        return $this->userPasswordHasherInterface->isPasswordValid($user, $password);
    }

}