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
use Symfony\Component\Serializer\SerializerInterface;

class ShowUserController extends AbstractController
{
    #[Route('/api/users/show-user', name: 'show_user', methods: ['GET'])]
    public function __invoke(
        Request $request,
        UserService $userService,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ): JsonResponse {
        $userEmail = $request->attributes->get('email');

        if (! $userEmail) {
            return ApiResponse::error(
                "missing-parameter",
                "Missing Parameter",
                "Email parameter is missing in the request.",
                "Email is required to fetch user data.",
                HttpStatusCodes::BAD_REQUEST
            );
        }

        try {
            $user = $userService->userExists($userEmail);

            if (! $user) {
                return ApiResponse::error(
                    "user-not-found",
                    "User Not Found",
                    "No user found with the given email.",
                    "Please check the email and try again.",
                    HttpStatusCodes::NOT_FOUND
                );
            }

            $jsonUser = $serializer->serialize($user, 'json');
            return ApiResponse::success(json_decode($jsonUser, true));
        } catch (\Exception $e) {
            $logger->error('Error fetching user', ['message' => $e->getMessage()]);
            return ApiResponse::error(
                "internal-server-error",
                "Unexpected Error",
                "An error occurred while retrieving user information.",
                $e->getMessage(),
                HttpStatusCodes::SERVER_ERROR
            );
        }
    }
}
