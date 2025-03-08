<?php
namespace App\Service;

use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\UserRepository;
use App\Utils\HttpStatusCodes;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private SerializerInterface $serializer;
    private CacheItemPoolInterface $cache;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, SerializerInterface $serializer, CacheItemPoolInterface $cache)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->cache          = $cache;
        $this->serializer     = $serializer;
    }
    public function createUser(array $data): string | null
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if (null !== $user) {
            throw new ApiException(
                "Registration Canceled",
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
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);

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

    public function getUserData(string $email): string | null
    {
        $cacheKey  = 'user_' . md5($email);
        $cacheItem = $this->cache->getItem($cacheKey);

        if (! $cacheItem->isHit()) {
            $user           = $this->userRepository->findOneBy(['email' => $email]);
            $serializedUser = $this->serializer->serialize($user, 'json');
            $cacheItem->set($serializedUser);
            $cacheItem->expiresAfter(3600);
            $this->cache->save($cacheItem);
        }

        return $cacheItem->get() ?? null;
    }
}
