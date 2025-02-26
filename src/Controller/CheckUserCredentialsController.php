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
            return ApiResponse::error([
                "title"   => "Invalid JSON Payload",
                "detail"  => "The request body is not a valid JSON",
                "message" => "Invalid data provided",
            ], HttpStatusCodes::BAD_REQUEST);
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
                return ApiResponse::error([
                    "title"   => $e->getTitle(),
                    "detail"  => $e->getDetail(),
                    "message" => $e->getMessage(),
                ], $e->getStatusCode());
            }

            return ApiResponse::error([
                "title"   => "Unexpected Error",
                "detail"  => "An unexpected error occurred while checking the user's credentials",
                "message" => $e->getMessage(),
            ], HttpStatusCodes::SERVER_ERROR);
        }
    }
}
