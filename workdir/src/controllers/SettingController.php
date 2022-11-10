<?php

namespace firesnake\isItRunning\controllers;

use firesnake\isItRunning\entities\User;
use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\Response;
use firesnake\isItRunning\http\TextResponse;
use firesnake\isItRunning\http\TwigResponse;
use firesnake\isItRunning\IsItRunning;

class SettingController
{
    public function overview(RequestEvent $requestEvent): Response
    {
        return new TwigResponse('settings/overview.html.twig', [
            'authenticatedUser' => $this->getAuthenticatedUser($requestEvent)
        ]);
    }

    private function getAuthenticatedUser(RequestEvent $event) :?User
    {
        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        return $isItRunning->getAuthenticatedUser();
    }
}