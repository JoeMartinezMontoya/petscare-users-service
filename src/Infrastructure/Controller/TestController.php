<?php
namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/public/api/users/test', name: 'test', methods: ['GET'])]
    public function __invoke()
    {
        $redis = RedisAdapter::createConnection($_ENV['CACHE_HOST']);
        $redis->set('test', 'ok');
        echo $redis->get('test');

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path'    => 'src/Controller/TestController.php',
        ]);
    }
}
