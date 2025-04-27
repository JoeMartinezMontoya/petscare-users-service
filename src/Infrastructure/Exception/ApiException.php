<?php
namespace App\Infrastructure\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    public function __construct(
        private string $type,
        private string $title,
        private string $detail,
        private int $status = 400,
        private ?array $errors = null,
        private ?string $instance = null
    ) {
        $this->type     = $type;
        $this->title    = $title;
        $this->detail   = $detail;
        $this->errors   = $errors;
        $this->instance = $instance ?? '';

        parent::__construct($status, $detail);
    }

    public function getType(): string
    {
        return $this->type;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDetail(): string
    {
        return $this->detail;
    }
    public function getErrors(): ?array
    {
        return $this->errors ?? null;
    }
    public function getInstance(): string
    {
        return $this->instance;
    }

    public static function fromRequest(
        RequestStack $requestStack,
        string $type,
        string $title,
        string $detail,
        int $status = 400,
        ?array $errors = null
    ): self {
        $request  = $requestStack->getCurrentRequest();
        $instance = $request ? $request->getUri() : '';

        return new self($type, $title, $detail, $status, $errors, $instance);
    }

    public function toResponse(): JsonResponse
    {
        return new JsonResponse([
            "type"     => $this->type,
            "title"    => $this->title,
            "status"   => $this->getStatusCode(),
            "detail"   => $this->detail,
            "instance" => $this->instance,
            "errors"   => $this->errors ?? [],
        ], $this->getStatusCode());
    }
}
