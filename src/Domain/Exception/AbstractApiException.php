<?php
namespace App\Domain\Exception;

abstract class AbstractApiException extends \Exception
{
    abstract public function getType(): string;
    abstract public function getTitle(): string;
    abstract public function getDetail(): string;
    abstract public function getStatusCode(): int;
    abstract public function getErrors(): ?array;
}
