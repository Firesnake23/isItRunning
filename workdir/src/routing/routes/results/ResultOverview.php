<?php

namespace firesnake\isItRunning\routing\routes\results;

use firesnake\isItRunning\controllers\ResultController;
use firesnake\isItRunning\routing\DefaultUrlParameterDefinition;
use firesnake\isItRunning\routing\permissions\LoginPermission;
use firesnake\isItRunning\routing\Route;

class ResultOverview implements Route
{

    public function getUrl(): string
    {
        return '/environmentResults';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getParameterDefinitions(): array
    {
        return [
            new DefaultUrlParameterDefinition('int')
        ];
    }

    public function getController(): string
    {
        return ResultController::class;
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