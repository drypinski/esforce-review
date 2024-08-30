<?php

namespace App\EventListener;

use App\Exception\UserNotFoundException;
use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionListener
{
    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $event->setResponse(new JsonResponse(['errors' => $exception->getErrors()], Response::HTTP_BAD_REQUEST));
        } elseif ($exception instanceof NotFoundHttpException || $exception instanceof UserNotFoundException) {
            $event->setResponse(new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_NOT_FOUND));
        }
    }
}
