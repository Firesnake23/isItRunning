<?php

namespace firesnake\isItRunning\routing\routes\environment;

use firesnake\isItRunning\controllers\EnvironmentController;
use firesnake\isItRunning\controllers\IndexController;
use firesnake\isItRunning\routing\DefaultUrlParameterDefinition;
use firesnake\isItRunning\routing\permissions\LoginPermission;
use firesnake\isItRunning\routing\Route;

class Delete implements Route
{

    public function getUrl(): string
    {
        return '/environment/delete';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getParameterDefinitions(): array
    {
        return [new DefaultUrlParameterDefinition('int')];
    }

    public function getController(): string
    {
        return EnvironmentController::class;
    }

    public function getMethod(): string
    {
        return 'delete';
    }

    public function protectedBy(): array
    {
        return [
            LoginPermission::class
        ];
    }
}