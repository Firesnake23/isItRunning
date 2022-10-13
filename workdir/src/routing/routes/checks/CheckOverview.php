<?php

namespace firesnake\isItRunning\routing\routes\checks;

use firesnake\isItRunning\controllers\CheckController;
use firesnake\isItRunning\controllers\EnvironmentController;
use firesnake\isItRunning\controllers\IndexController;
use firesnake\isItRunning\routing\permissions\LoginPermission;
use firesnake\isItRunning\routing\Route;

class CheckOverview implements Route
{

    public function getUrl(): string
    {
        return '/checks';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getParameterDefinitions(): array
    {
        return [];
    }

    public function getController(): string
    {
        return CheckController::class;
    }

    public function getMethod(): string
    {
        return 'overview';
    }

    public function protectedBy(): array
    {
        return [
            LoginPermission::class
        ];
    }
}