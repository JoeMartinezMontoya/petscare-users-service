<?php
namespace App\Domain\ValueObjects;

use App\Domain\Exception\DomainRuleViolationException;

final class UserName
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ('' == $value) {
            throw new DomainRuleViolationException('Username can not be empty.');
        }

        if (strlen($value) > 20) {
            throw new DomainRuleViolationException('Username can not contain more than 20 characters.');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {return $this->value;}

    public function __toString(): string
    {return $this->value;}

    public function equals(UserName $other): bool
    {return $other->getValue() === $this->getValue();}
}
