<?php
namespace App\Controller;

use App\Service\UserService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GetUsernameController extends AbstractController
{
    #[Route('/public/api/users/{id}/username', name: 'get_username', methods: ['GET'])]
    public function __invoke(int $id, UserService $service)
    {
        $username = $service->getUsername($id);
        return ApiResponse::success(['user_name' => $username]);
    }
}
