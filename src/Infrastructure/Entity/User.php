<?php
namespace App\Infrastructure\Entity;

use App\Infrastructure\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::STRING, unique: true)]
    private string $id;

    #[ORM\Column(name: 'userName', type: Types::STRING)]
    private string $userName;

    #[ORM\Column(name: 'firstName', type: Types::STRING)]
    private string $firstName;

    #[ORM\Column(name: 'lastName', type: Types::STRING)]
    private string $lastName;

    #[ORM\Column(name: 'birthDate', type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $birthDate;

    #[ORM\Column(name: 'createdAt', type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName($userName): User
    {
        $this->userName = $userName;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName($firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName($lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getBirthDate(): \DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate($birthDate): User
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
