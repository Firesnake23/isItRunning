<?php

namespace firesnake\isItRunning\routing\routes\settings;

use firesnake\isItRunning\controllers\SettingController;
use firesnake\isItRunning\routing\permissions\LoginPermission;
use firesnake\isItRunning\routing\Route;
use firesnake\isItRunning\routing\RoutePermission;

class SettingOverviewRoute implements Route
{

    public function getUrl(): string
    {
        return '/settings';
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
        return SettingController::class;
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