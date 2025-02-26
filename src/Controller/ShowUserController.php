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
                "Missing Parameter",
                "Email parameter is missing in the request.",
                "Email is required to fetch user data.",
                HttpStatusCodes::BAD_REQUEST
            );
        }

        try {
            $user     = $userService->userExists($userEmail);
            $jsonUser = $serializer->serialize($user, 'json');
            return ApiResponse::success(["user" => json_decode($jsonUser, true)], HttpStatusCodes::SUCCESS);
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
