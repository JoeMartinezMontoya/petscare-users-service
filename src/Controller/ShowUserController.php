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
            return ApiResponse::error([
                "title"   => "Missing Parameter",
                "detail"  => "Email parameter is missing in the request",
                "message" => "Email is required to fetch user data",
            ], HttpStatusCodes::BAD_REQUEST);
        }

        try {
            $user     = $userService->userExists($userEmail);
            $jsonUser = $serializer->serialize($user, 'json');
            return ApiResponse::success(["user" => json_decode($jsonUser, true)], HttpStatusCodes::SUCCESS);
        } catch (\Exception $e) {
            if ($e instanceof ApiException) {
                if ($e instanceof ApiException) {
                    return ApiResponse::error([
                        "title"   => $e->getTitle(),
                        "detail"  => $e->getDetail(),
                        "message" => $e->getMessage(),
                    ], $e->getStatusCode());
                }

                return ApiResponse::error([
                    "title"   => "Unexpected Error",
                    "detail"  => "An unexpected error occurred while fetching the user's data",
                    "message" => $e->getMessage(),
                ], HttpStatusCodes::SERVER_ERROR);
            }
        }
    }
}
