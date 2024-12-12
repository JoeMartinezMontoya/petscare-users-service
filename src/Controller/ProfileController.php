<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    #[Route('/api/user/profile', name: 'api_user_profile', methods: ['GET'])]
    public function profile(Security $security): JsonResponse
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        return $this->json([
            'debug' => [
                'class' => get_class($user),
                'roles' => $user->getRoles(),
            ],
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ]);
    }
}
