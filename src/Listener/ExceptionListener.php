<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class ExceptionListener
 * @package App\Exception
 */
class ExceptionListener
{
    protected $kernel;

    /**
     * ExceptionListener constructor.
     * @param $kernel
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $response = new Response();
        if ($exception instanceof HttpExceptionInterface) {
            $content = [
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ];

            if ('dev' == $this->kernel->getEnvironment()) {
                $content['trace'] = $exception->getTrace();
                $content['line'] = $exception->getLine();
            }

            $response->setContent(json_encode($content));
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(500);
        }

        $event->setResponse($response);
    }
}