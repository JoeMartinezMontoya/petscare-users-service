<?php
namespace App\Controller;

use App\Exception\ApiException;
use App\Service\UserService;
use App\Utils\ApiResponse;
use App\Utils\HttpStatusCodes;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateUserController extends AbstractController
{
    #[Route('/api/users/create-user', name: 'create_user', methods: ['POST'])]
    public function __invoke(Request $request, UserService $userService, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (! $data) {
            return ApiResponse::error(
                "invalid-json",
                "Invalid JSON Payload",
                "The request body is not a valid JSON",
                "Invalid data provided",
                HttpStatusCodes::BAD_REQUEST
            );
        }

        try {
            $response = $userService->createUser($data);
            return ApiResponse::success([
                "detail"  => "User created",
                "message" => "$response account has been successfully created",
            ], HttpStatusCodes::CREATED);
        } catch (\Exception $e) {
            $logger->error('User creation failed', ['message' => $e->getMessage()]);
            if ($e instanceof ApiException) {
                return ApiResponse::error(
                    "registration-error",
                    $e->getTitle(),
                    $e->getDetail(),
                    $e->getCustomMessage(),
                    $e->getStatusCode()
                );
            }
            return ApiResponse::error(
                "auth-service-error",
                "Unexpected Error",
                "An unexpected error occurred while creating the user",
                $e->getMessage(),
                HttpStatusCodes::SERVER_ERROR
            );
        }
    }
}
