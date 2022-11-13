<?php

namespace firesnake\isItRunning\events;

use firesnake\isItRunning\IsItRunning;

class EventHandler
{
    private IsItRunning $isItRunning;
    public function __construct(IsItRunning $isItRunning)
    {
        $this->isItRunning = $isItRunning;
    }

    public function fire(AbstractEvent $event) {
        $event->setParam('isItRunning', $this->isItRunning);
        $event->invoke();
    }
}