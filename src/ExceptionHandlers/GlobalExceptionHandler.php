<?php

declare(strict_types=1);

namespace App\ExceptionHandlers;

use App\Constants\ErrorMessages;
use App\Exception\ServiceException;
use App\Exception\StorageException;
use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class GlobalExceptionHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            return;
        } elseif ($exception instanceof ServiceException) {
            $response = new JsonResponse([
                'error' => ErrorMessages::SERVICE_ERROR,
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

            $event->setResponse($response);
        } elseif ($exception instanceof StorageException) {
            $response = new JsonResponse([
                'error' => ErrorMessages::STORAGE_ERROR,
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

            $event->setResponse($response);
        } elseif ($exception instanceof \InvalidArgumentException) {
            $response = new JsonResponse([
                'error' => $exception->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);

            $event->setResponse($response);
        } else {
            $response = new JsonResponse([
                'error' => ErrorMessages::INTERNAL_SERVER_ERROR,
                'message' => ErrorMessages::UNEXPECTED_ERROR_MESSAGE
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

            $event->setResponse($response);
        }
    }
}
