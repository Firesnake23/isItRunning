<?php

namespace firesnake\isItRunning\routing\routes;

use firesnake\isItRunning\controllers\IndexController;
use firesnake\isItRunning\routing\Route;

class IndexRoute implements Route
{

    public function getUrl(): string
    {
        return '/';
    }

    public function getAliases(): array
    {
        return [
            '',
            '/index.php',
            '/index'
        ];
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
        return 'index';
    }

    public function protectedBy(): array
    {
        return [];
    }
}