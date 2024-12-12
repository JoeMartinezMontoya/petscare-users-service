<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('api/users', name: 'create_user_old', methods: ['POST'])]
    public function createUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['error' => 'L\'email et le mot de passe sont requis.'], 400);
        }

        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'Cet utilisateur existe déjà.'], 409);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Utilisateur créé avec succès.'], 201);
    }

    #[Route('api/user-repo', name: 'user_repo', methods: ['POST'])]
    public function userRepository(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['error' => 'Identifiants incorrects'], 401);
        }


        return new JsonResponse(['message' => 'Utilisateur identifié.'], 200);
    }

    #[Route('api/get-user-data', name: 'get_user_data', methods: ['POST'])]
    public function getUserData(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        return new JsonResponse($user, 200);
    }

    #[Route('/api/debug', name: 'user_debug', methods: ['POST'])]
    public function debug(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        // $data = json_decode($request->getContent(), true);

        // $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        return new JsonResponse($request, 200);
    }
}
