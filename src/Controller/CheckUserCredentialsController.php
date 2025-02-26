<?php
namespace App\Controller;

use App\Service\UserService;
use App\Utils\ApiResponse;
use App\Utils\HttpStatusCodes;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CheckUserCredentialsController extends AbstractController
{
    #[Route('/api/users/check-user-credentials', name: 'check_user_credentials', methods: ['POST'])]
    public function __invoke(Request $request, UserService $userService, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (! $data) {
            return ApiResponse::error(
                "invalid-json",
                "Invalid JSON Payload",
                "The request body is not a valid JSON.",
                "Invalid data provided.",
                HttpStatusCodes::BAD_REQUEST
            );
        }

        try {
            $response = $userService->checkUserCredentials($data);

            if (HttpStatusCodes::SUCCESS !== $response['status']) {
                return ApiResponse::error(
                    "invalid-credentials",
                    $response['title'] ?? "Authentication Failed",
                    $response['detail'] ?? "Invalid user credentials.",
                    $response['message'] ?? "Please check your email and password.",
                    $response['status']
                );
            }

            return ApiResponse::success([
                'email' => $response['email'],
            ], HttpStatusCodes::SUCCESS);
        } catch (\Exception $e) {
            $logger->error('User authentication failed', ['message' => $e->getMessage()]);
            return ApiResponse::error(
                "internal-server-error",
                "Unexpected Error",
                "An unexpected error occurred while checking credentials.",
                $e->getMessage(),
                HttpStatusCodes::SERVER_ERROR
            );
        }
    }
}
