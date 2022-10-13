<?php

namespace firesnake\isItRunning\routing\routes;

use firesnake\isItRunning\controllers\IndexController;
use firesnake\isItRunning\routing\DefaultUrlParameterDefinition;
use firesnake\isItRunning\routing\Route;

class LoginRoute implements Route
{

    public function getUrl(): string
    {
        return '/login';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getParameterDefinitions(): array
    {
        return [
            new DefaultUrlParameterDefinition('username'),
            new DefaultUrlParameterDefinition('password')
        ];
    }

    public function getController(): string
    {
        return IndexController::class;
    }

    public function getMethod(): string
    {
        return 'login';
    }

    public function protectedBy(): array
    {
        return [];
    }
}