<?php
namespace App\Domain\ValueObjects;

final class CreatedAt
{
    public function __construct(private \DateTimeImmutable $value)
    {$this->value = $value;}

    public function getValue(): \DateTimeImmutable
    {return $this->value;}

    public function __toString(): string
    {return $this->value->format('Y-m-d');}

    public function equals(CreatedAt $other): bool
    {return $other->getValue() == $this->value;}
}
