<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;

class CheckUserCredentialsController extends AbstractController
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private UserService $userService;

    public function __construct(UserService $userService, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userService = $userService;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    #[Route('/api/users/check-user-credentials', name: 'check_user_credentials', methods: 'POST')]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->userService->checkUserCredentials($data['email'], $data['password']);

        return new JsonResponse([
            'success' => $user['success'],
            'message' => $user['message'],
            'source' => 'CheckUserCredentialsController',
            'code' => $user['code']
        ], 200);
    }
}