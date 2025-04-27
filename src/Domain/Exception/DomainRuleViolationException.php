<?php
namespace App\Domain\Exception;

use App\Infrastructure\Utils\HttpStatusCodes;

final class DomainRuleViolationException extends AbstractApiException
{
    public function __construct(private string $detail = "Invalid data provided.")
    {parent::__construct($this->detail);}
    public function getType(): string
    {return 'https://api.petscare.com/error/domain-rule-violation';}
    public function getTitle(): string
    {return 'Domain Rule Violation';}
    public function getDetail(): string
    {return $this->detail;}
    public function getStatusCode(): int
    {return HttpStatusCodes::BAD_REQUEST;}
    public function getErrors(): ?array
    {return null;}
}
