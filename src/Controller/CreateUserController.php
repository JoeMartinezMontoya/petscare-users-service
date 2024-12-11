<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Service\UserService;

class CreateUserController extends AbstractController
{
    private UserService $userService;
    private LoggerInterface $loggerInterface;

    public function __construct(UserService $userService, LoggerInterface $loggerInterface)
    {
        $this->userService = $userService;
        $this->loggerInterface = $loggerInterface;
    }

    #[Route('/api/users/create-user', name: 'create_user', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $this->loggerInterface->info("Contenu de la requête: " . json_encode($data));

        if (!$data || !isset($data['email'], $data['password'])) {
            return new JsonResponse([
                "source" => 'CreateUserController',
                "type" => "https://example.com/probs/invalid-data",
                "title" => "Données invalide",
                "status" => Response::HTTP_BAD_REQUEST,
                "detail" => "Une adresse mail et un mot de passe sont requis",
                "message" => "Invalid input data for registration."
            ], Response::HTTP_BAD_REQUEST);
        }

        $result = $this->userService->createUser($data);

        $this->loggerInterface->info("Resultat: " . json_encode($result));

        return new JsonResponse([
            "source" => 'CreateUserController',
            "type" => "https://example.com/probs/invalid-data",
            "title" => $result['title'],
            "status" => $result['status'],
            "detail" => $result['detail'],
            "message" => $result['message']
        ], Response::HTTP_OK);
    }
}