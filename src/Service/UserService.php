<?php

namespace App\Service;

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

    public function findByEmail(string $email): array|null
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return null;
        }

        return [
            'id' => $user->getId(),
            'email' => $user->getEmail()
        ];
    }

    public function createUser(array $data)
    {
        $existingUser = $this->userRepository->findOneBy(['email' => $data['email']]);

        if ($existingUser) {
            return [
                'success' => false,
                'message' => "L'utilisateur existe déjà",
                'source' => 'UserService::CreateUser'
            ];
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, $data['password']));

        $this->userRepository->persistUser($user);

        return [
            'success' => true,
            'message' => 'Utilisateur enregistré en BDD',
            'source' => 'UserService::createUser'
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