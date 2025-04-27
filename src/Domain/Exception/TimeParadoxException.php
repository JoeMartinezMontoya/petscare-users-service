<?php
namespace App\Domain\Exception;

use App\Infrastructure\Utils\HttpStatusCodes;

final class TimeParadoxException extends AbstractApiException
{
    public function __construct(private string $detail = "Time paradox detected.")
    {parent::__construct($this->detail);}
    public function getType(): string
    {return 'https://api.petscare.com/error/time-paradox';}
    public function getTitle(): string
    {return 'Time Paradox';}
    public function getDetail(): string
    {return $this->detail;}
    public function getStatusCode(): int
    {return HttpStatusCodes::BAD_REQUEST;}
    public function getErrors(): ?array
    {return null;}
}
