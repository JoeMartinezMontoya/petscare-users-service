<?php
namespace App\Infrastructure\EventListener;

use App\Domain\Exception\AbstractApiException;
use App\Infrastructure\Exception\ApiException;
use App\Infrastructure\Utils\ApiResponse;
use App\Infrastructure\Utils\HttpStatusCodes;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiException) {
            $event->setResponse($exception->toResponse());
            return;
        }

        if ($exception instanceof AbstractApiException) {
            $request  = $this->requestStack->getCurrentRequest();
            $instance = $request?->getUri();

            $response = ApiResponse::error(
                $exception->getType(),
                $exception->getTitle(),
                $exception->getDetail(),
                $exception->getStatusCode(),
                $instance,
                $exception->getErrors()
            );

            $event->setResponse($response);
            return;
        }

        $event->setResponse(ApiResponse::error(
            'https://api.petscare.com/error/internal-error',
            'Internal Error',
            $exception->getMessage(),
            HttpStatusCodes::SERVER_ERROR,
            $this->requestStack->getCurrentRequest()?->getUri(),
            []
        ));

        // $event->setResponse(ApiResponse::error(
        //     'https://api.petscare.com/error/internal-error',
        //     'Internal Error',
        //     'Unexcpeted error',
        //     HttpStatusCodes::SERVER_ERROR,
        //     $this->requestStack->getCurrentRequest()?->getUri(),
        //     []
        // ));
    }
}
