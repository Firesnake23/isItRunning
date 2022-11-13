<?php

namespace firesnake\isItRunning\http;

use firesnake\isItRunning\events\AbstractEvent;

abstract class AbstractResponse implements Response
{
    private AbstractEvent $event;

    final public function setEvent(AbstractEvent $event) {
        $this->event = $event;
    }

    final public function getEvent() : AbstractEvent
    {
        return $this->event;
    }
}