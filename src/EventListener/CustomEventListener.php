<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CustomEventListener
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelResponse(ResponseEvent $event) 
    {
        $response = $event->getResponse();

        $this->logger->info(
            "On renvoi une réponse avec le code : " . 
            $response->getStatusCode()
        );
    }
}