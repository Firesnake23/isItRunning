<?php

namespace firesnake\isItRunning\events;

use firesnake\isItRunning\routing\Request;

class RequestEvent extends AbstractEvent
{
    private Request $request;
    private string $controllerMethod;

    public function __construct(Request $request, string $controllerMethod)
    {
        $this->request = $request;
        $this->controllerMethod = $controllerMethod;
    }

    public function getRequest() :Request
    {
        return $this->request;
    }

    public function invoke(): void
    {
        $controller = explode('::', $this->controllerMethod);
        $controllerClass = new \ReflectionClass($controller[0]);

        $controllerInstance = $controllerClass->newInstance();
        $controllerMethod = $controllerClass->getMethod($controller[1]);
        $response = $controllerMethod->invoke($controllerInstance, $this);
        if($response !== null) {
            $response->setEvent($this);
            $this->setResponse($response);
        }
    }
}