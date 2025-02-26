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

class CheckUserCredentialsController extends AbstractController
{
    #[Route('/api/users/check-user-credentials', name: 'check_user_credentials', methods: ['POST'])]
    public function __invoke(Request $request, UserService $userService, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (! $data) {
            return ApiResponse::error(
                "Invalid JSON Payload",
                "The request body is not a valid JSON",
                "Invalid data provided",
                HttpStatusCodes::BAD_REQUEST
            );
        }

        try {

            $response = $userService->checkUserCredentials($data);
            return ApiResponse::success([
                "detail"  => "Login successful",
                "message" => "Welcome back",
                "mail"    => $response,
            ], HttpStatusCodes::SUCCESS);

        } catch (\Exception $e) {

            if ($e instanceof ApiException) {
                return ApiResponse::error(
                    $e->getTitle(),
                    $e->getDetail(),
                    $e->getMessage(),
                    $e->getStatusCode()
                );
            }

            return ApiResponse::error(
                "Unexpected Error",
                "An unexpected error occurred while creating the user",
                $e->getMessage(),
                HttpStatusCodes::SERVER_ERROR
            );
        }
    }
}
