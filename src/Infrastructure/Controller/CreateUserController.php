<?php
namespace App\Infrastructure\Controller;

use App\Application\UseCase\CreateUser;
use App\Infrastructure\Exception\ApiException;
use App\Infrastructure\Utils\ApiResponse;
use App\Infrastructure\Utils\HttpStatusCodes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CreateUserController extends AbstractController
{
    #[Route(path: '/public/api/registration', name: 'registration', methods: ['POST'])]
    public function __invoke(Request $request, CreateUser $useCase): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data)) {
            throw new ApiException(
                'https://api.petscare.com/error/invalid-data',
                'Invalid Data',
                'No data received.',
                HttpStatusCodes::BAD_REQUEST,
                null,
                '/public/api/registration'
            );
        }

        $response = $useCase->execute($data);
        return ApiResponse::success([$response], HttpStatusCodes::CREATED);
    }
}
