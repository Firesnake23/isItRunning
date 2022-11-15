<?php

namespace firesnake\isItRunning\routing\routes;

use firesnake\isItRunning\controllers\IndexController;
use firesnake\isItRunning\routing\DefaultUrlParameterDefinition;
use firesnake\isItRunning\routing\Route;

class LibJsRoute implements Route
{

    public function getUrl(): string
    {
        return '/js/lib.js';
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
        return 'libJs';
    }

    public function protectedBy(): array
    {
        return [];
    }
}