<?php
namespace App\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse
{
    public static function success(array $data, int $status = HttpStatusCodes::SUCCESS): JsonResponse
    {
        return new JsonResponse(array_merge([
            "type"    => "https://example.com/probs/success",
            "title"   => "Success",
            "detail"  => $data['detail'] ?? "Operation completed successfully.",
            "message" => $data['message'] ?? "Request processed successfully.",
        ], $data), $status);
    }

    public static function error(string $type, string $title = "An error occurred", string $detail = "No details provided", string $message = "An unexpected error occurred", int $status = HttpStatusCodes::SERVER_ERROR): JsonResponse
    {
        return new JsonResponse([
            "type"    => "https://example.com/probs/$type",
            "title"   => $title,
            "detail"  => $detail,
            "message" => $message,
        ], $status);
    }
}
