<?php
namespace App\Infrastructure\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse
{
    public static function success(array $data, int $status = HttpStatusCodes::SUCCESS, array $meta = []): JsonResponse
    {
        return new JsonResponse([
            "type"   => "https://api.petscare.com/success",
            "title"  => "Success",
            "status" => $status,
            "detail" => "Operation completed successfully.",
            "data"   => $data,
            "meta"   => $meta,
        ], $status);
    }

    public static function error(
        string $type,
        string $title,
        string $detail,
        int $status,
        ?string $instance = null,
        ?array $errors = []
    ): JsonResponse {
        return new JsonResponse([
            "type"     => $type,
            "title"    => $title,
            "status"   => $status,
            "detail"   => $detail,
            "instance" => $instance,
            "errors"   => $errors,
        ], $status);
    }
}
