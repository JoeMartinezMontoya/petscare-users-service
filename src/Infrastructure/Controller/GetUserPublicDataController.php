<?php
namespace App\Infrastructure\Controller;

use App\Service\UserService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetUserPublicDataController extends AbstractController
{

    #[Route('/private/api/users/get-public-data/{id}', name: 'get_user_public_data', methods: ['GET'])]
    public function __invoke(int $id, UserService $service): JsonResponse
    {
        $response = $service->getUserPublicData($id);
        return ApiResponse::success(['userData' => $response]);
    }
}
