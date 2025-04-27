<?php
namespace App\Application\Exception;

use App\Domain\Exception\AbstractApiException;
use App\Infrastructure\Utils\HttpStatusCodes;

final class InvalidDataFormatException extends AbstractApiException
{
    public function __construct(private string $detail = 'Data can not be processed.')
    {}
    public function getType(): string
    {return 'https://api.petscare.com/error/invalid-data-format';}
    public function getTitle(): string
    {return 'Invalid Data Format';}
    public function getDetail(): string
    {return $this->detail;}
    public function getStatusCode(): int
    {return HttpStatusCodes::BAD_REQUEST;}
    public function getErrors(): ?array
    {return null;}
}
