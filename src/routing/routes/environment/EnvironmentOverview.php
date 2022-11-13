<?php

namespace firesnake\isItRunning\routing\routes\environment;

use firesnake\isItRunning\controllers\EnvironmentController;
use firesnake\isItRunning\controllers\IndexController;
use firesnake\isItRunning\routing\permissions\LoginPermission;
use firesnake\isItRunning\routing\Route;

class EnvironmentOverview implements Route
{

    public function getUrl(): string
    {
        return '/environments';
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
        return EnvironmentController::class;
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