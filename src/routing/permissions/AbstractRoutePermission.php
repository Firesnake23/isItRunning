<?php

namespace firesnake\isItRunning\routing\permissions;

use firesnake\isItRunning\IsItRunning;
use firesnake\isItRunning\routing\RoutePermission;

abstract class AbstractRoutePermission implements RoutePermission
{
    private IsItRunning $isItRunning;

    public function __construct(IsItRunning $isItRunning) {
        $this->isItRunning = $isItRunning;
    }

    protected function getIsItRunning() :IsItRunning
    {
        return $this->isItRunning;
    }
}