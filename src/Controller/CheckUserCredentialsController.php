<?php
namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckUserCredentialsController extends AbstractController
{
    #[Route('/api/users/check-user-credentials', name: 'check_user_credentials', methods: 'POST')]
    public function __invoke(Request $request, UserService $userService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $response = $userService->checkUserCredentials($data);

        $data = [
            "source"  => "UserService::checkUserCredentials",
            "type"    => "https://example.com/probs/invalid-data",
            "title"   => $response['title'],
            "status"  => $response['status'],
            "detail"  => $response['detail'],
            "message" => $response['message'],
        ];

        if (Response::HTTP_OK === $response['status']) {
            $data['email'] = $response['email'];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
