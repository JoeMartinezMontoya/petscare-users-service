<?php
namespace App\Domain\ValueObjects;

use App\Domain\Exception\DomainRuleViolationException;

final class Id
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ('' == $value) {
            throw new DomainRuleViolationException('Id can not be empty.');
        }

        if (! preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/", $value)) {
            throw new DomainRuleViolationException('Id format not valid.');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {return $this->value;}

    public function __toString(): string
    {return $this->value;}

    public function equals(Id $other): bool
    {return $other->getValue() === $this->value;}
}
