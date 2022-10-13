<?php

namespace firesnake\isItRunning\routing\permissions;

use firesnake\isItRunning\routing\RoutePermission;

class LoginPermission extends AbstractRoutePermission
{

    public function getName(): string
    {
        return 'login';
    }

    public function getDisplayName(): string
    {
        return $this->getName();
    }

    public function errorCode(): int
    {
        return 403;
    }

    public function checkPermission(): bool
    {
        return $this->getIsItRunning()->getAuthenticatedUser() != null;
    }
}