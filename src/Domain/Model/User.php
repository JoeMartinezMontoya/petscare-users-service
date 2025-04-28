<?php
namespace App\Domain\Model;

use App\Domain\ValueObjects\BirthDate;
use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\FirstName;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\LastName;
use App\Domain\ValueObjects\UserName;

class User
{
    public function __construct(
        private Id $id,
        private UserName $userName,
        private FirstName $firstName,
        private LastName $lastName,
        private BirthDate $birthDate,
        private CreatedAt $createdAt,
    ) {}

    public function getId(): Id
    {return $this->id;}

    public function getUserName(): UserName
    {return $this->userName;}

    public function getFirstName(): FirstName
    {return $this->firstName;}

    public function getLastName(): LastName
    {return $this->lastName;}

    public function getBirthDate(): BirthDate
    {return $this->birthDate;}

    public function getCreatedAt(): CreatedAt
    {return $this->createdAt;}
}
