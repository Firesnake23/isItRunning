<?php

namespace firesnake\isItRunning\events;

use firesnake\isItRunning\http\Response;

abstract class AbstractEvent
{
    private ?Response $response = null;
    private $paramBag = [];

    public function setResponse(Response $response) {
        $this->response = $response;
    }

    public function getResponse() :?Response
    {
        return $this->response;
    }

    public final function setParam(string $key, mixed $object)
    {
        $this->paramBag[$key] = $object;
    }

    public final function getParam(string $key) :mixed
    {
        if(isset($this->paramBag[$key])) {
            return $this->paramBag[$key];
        }
        return null;
    }

    public abstract function invoke();
}