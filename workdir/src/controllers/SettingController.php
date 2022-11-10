<?php

namespace firesnake\isItRunning\controllers;

use firesnake\isItRunning\entities\User;
use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\http\Response;
use firesnake\isItRunning\http\TextResponse;
use firesnake\isItRunning\http\TwigResponse;
use firesnake\isItRunning\IsItRunning;

class SettingController
{
    public function overview(RequestEvent $requestEvent): Response
    {
        return new TwigResponse('settings/overview.html.twig', [
            'authenticatedUser' => $this->getAuthenticatedUser($requestEvent),
            'mail' => $this->getAuthenticatedUser($requestEvent)->getMail()
        ]);
    }

    public function changeMail(RequestEvent $event): Response {
        $authenticatedUser = $this->getAuthenticatedUser($event);

        if($authenticatedUser == null) {
            return new RedirectResponse('/');
        }

        if(isset($event->getRequest()->getPost()['mail'])) {
            $mail = $event->getRequest()->getPost()['mail'];

            /** @var IsItRunning $isItRunning */
            $isItRunning = $event->getParam('isItRunning');
            $userManager = $isItRunning->getUserManager();

            $authenticatedUser->setMail($mail);
            $userManager->saveUser($authenticatedUser);
        }

        return new RedirectResponse('/settings');
    }

    private function getAuthenticatedUser(RequestEvent $event) :?User
    {
        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        return $isItRunning->getAuthenticatedUser();
    }
}