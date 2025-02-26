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
