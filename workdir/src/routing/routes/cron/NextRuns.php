<?php

namespace firesnake\isItRunning\routing\routes\cron;

use firesnake\isItRunning\controllers\CronController;
use firesnake\isItRunning\routing\DefaultUrlParameterDefinition;
use firesnake\isItRunning\routing\Route;

class NextRuns implements Route
{

    public function getUrl(): string
    {
        return '/cron/nextRuns';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getParameterDefinitions(): array
    {
        return [
            new DefaultUrlParameterDefinition('cronString')
        ];
    }

    public function getController(): string
    {
        return CronController::class;
    }

    public function getMethod(): string
    {
        return 'getRuns';
    }

    public function protectedBy(): array
    {
        return [];
    }
}