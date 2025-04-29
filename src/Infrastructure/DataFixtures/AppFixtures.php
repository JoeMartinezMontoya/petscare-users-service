<?php
namespace App\Infrastructure\DataFixtures;

use App\Infrastructure\Entity\UserProfile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    protected $faker;

    // php bin/console d:f:l
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        //admin
        $admin = (new UserProfile)
            ->setUserName('Admin')
            ->setFirstName('Joë')
            ->setLastName('Martinez Montoya')
            ->setBirthDate(new \DateTimeImmutable('1993-11-05T12:00:00+02:00'))
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($admin);
        for ($i = 0; $i <= 20; $i++) {
            $user = (new UserProfile())
                ->setUserName($faker->userName)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setBirthDate(new \DateTimeImmutable())
                ->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
        }
        $manager->flush();
    }
}
