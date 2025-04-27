<?php
namespace App\Infrastructure\DataFixtures;

use App\Infrastructure\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected $faker;

    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    // php bin/console d:f:l
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        //admin
        $admin = new User;
        $admin->setEmail('admin@petscare.com')
            ->setPassword($this->userPasswordHasherInterface->hashPassword($admin, 'password'))
            ->setUserName('Admin')
            ->setFirstName('Joë')
            ->setLastName('Martinez Montoya')
            ->setBirthDate(new \DateTimeImmutable('1993-11-05T12:00:00+02:00'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setRoles([
                'ROLE_USER',
                'ROLE_ADMIN',
            ]);
        $manager->persist($admin);
        for ($i = 0; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail($faker->freeEmail)
                ->setPassword($this->userPasswordHasherInterface->hashPassword($user, 'test'))
                ->setUserName($faker->userName)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setBirthDate(new \DateTimeImmutable())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setRoles([
                    'ROLE_USER',
                ]);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
