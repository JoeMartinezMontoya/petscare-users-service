<?php
namespace App\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse
{
    public static function success(array $data, int $status = HttpStatusCodes::SUCCESS): JsonResponse
    {
        return new JsonResponse(array_merge([
            "type"            => "users-service-success",
            "title"           => $data['title'] ?? "Success",
            "detail"          => $data['detail'] ?? "Operation completed successfully",
            "message"         => $data['message'] ?? "Request processed successfully",
            "response-status" => $status,
        ], $data), $status);
    }

    public static function error(array $data, int $status = HttpStatusCodes::SERVER_ERROR): JsonResponse
    {
        return new JsonResponse(array_merge([
            "type"            => "users-service-error",
            "title"           => $data['title'] ?? "Error",
            "detail"          => $data['detail'] ?? "Operation canceled",
            "message"         => $data['message'] ?? "Request canceled",
            "response-status" => $status,
        ], $data), $status);
    }
}
