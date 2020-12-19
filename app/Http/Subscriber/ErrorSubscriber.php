<?php

namespace App\Http\Subscriber;

use App\Lib\Exception\ValidateException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ErrorSubscriber implements EventSubscriberInterface
{
    public function onKernelThrowException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ValidateException) {
            $response = new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
            $event->setResponse($response);
        }
        if (!$exception instanceof HttpException) {
            
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelThrowException', 1024],
        ];
    }
}
