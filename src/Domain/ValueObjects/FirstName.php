<?php
namespace App\Domain\ValueObjects;

use App\Domain\Exception\DomainRuleViolationException;

final class FirstName
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ('' == $value) {
            throw new DomainRuleViolationException('Firstname can not be empty.');
        }

        if (((bool) preg_match('/\d/', $value))) {
            throw new DomainRuleViolationException('Lastname should not contain any numbers.');
        }

        if ((bool) strpbrk($value, "#$%^&*()+=[];,./{}|:<>?~")) {
            throw new DomainRuleViolationException('Lastname should not contain any special characters.');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {return $this->value;}

    public function __toString(): string
    {return $this->value;}

    public function equals(FirstName $other): bool
    {return $other->getValue() === $this->getValue();}
}
