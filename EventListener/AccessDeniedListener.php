<?php

namespace davidwnek\RedirectAccessControlBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedListener
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var array
     */
    private $config;

    /**
     * AccessDeniedListener constructor.
     * @param Router $router
     * @param array $config
     */
    public function __construct(Router $router, array $config)
    {
        $this->router = $router;
        $this->config = $config;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof AccessDeniedHttpException) {
            $request = $event->getRequest();

            $routeName = $request->get('_route');

            if(array_key_exists($routeName, $this->config)) {
                $event->setResponse(new RedirectResponse($this->router->generate($this->config[$routeName])));
            }
        }
    }
}