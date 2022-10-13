<?php

namespace firesnake\isItRunning\routing\routes;

use firesnake\isItRunning\controllers\IndexController;
use firesnake\isItRunning\routing\permissions\LoginPermission;
use firesnake\isItRunning\routing\Route;

class DashboardRoute implements Route
{

    public function getUrl(): string
    {
        return '/dashboard';
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
        return IndexController::class;
    }

    public function getMethod(): string
    {
        return 'dashboard';
    }

    public function protectedBy(): array
    {
        return [LoginPermission::class];
    }
}