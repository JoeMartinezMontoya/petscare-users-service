<?php
namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ShowUserController extends AbstractController
{
    private UserService $userService;
    private SerializerInterface $serializer;

    public function __construct(UserService $userService, SerializerInterface $serializer)
    {
        $this->userService = $userService;
        $this->serializer  = $serializer;
    }

    #[Route('/api/users/show-user', name: 'show-user', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $userEmail = $request->attributes->get('email'); // data sent by middleware
        if (! $userEmail) {
            return new JsonResponse(['error' => 'Utilisateur introuvable avec l\'email'], 404);
        }

        $user = $this->userService->userExists($userEmail);

        if (! $user) {
            return new JsonResponse(['error' => 'Utilisateur introuvable'], 404);
        }

        $jsonUser = $this->serializer->serialize($user, 'json');

        return new JsonResponse($jsonUser, 200, [], true); // true indique que $jsonUser est déjà du JSON
    }
}
