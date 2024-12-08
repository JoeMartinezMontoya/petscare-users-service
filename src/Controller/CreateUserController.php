<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;

class CreateUserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/api/users/create-user', name: 'create_user', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['email'], $data['password'])) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Données invalide',
                'source' => 'CreateUserController'
            ], 400);
        }

        $result = $this->userService->createUser($data);

        if (!$result['success']) {
            return new JsonResponse([
                'success' => false,
                'error' => $result['message'],
                'source' => 'CreateUserController'
            ], 400);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Utilisateur crée',
            'source' => 'CreateUserController'
        ], 201);
    }
}