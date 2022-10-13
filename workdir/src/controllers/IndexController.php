<?php

namespace firesnake\isItRunning\controllers;

use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\http\TwigResponse;
use firesnake\isItRunning\IsItRunning;

class IndexController
{
    public function index(RequestEvent $event): TwigResponse
    {
        return new TwigResponse('index/index.html.twig', []);
    }

    public function login(RequestEvent $event): RedirectResponse
    {
        $request = $event->getRequest();
        if (isset($request->getPost()['password']) && isset($request->getPost()['username'])) {
            /** @var IsItRunning $isItRunning */
            $isItRunning = $event->getParam('isItRunning');
            $userManager = $isItRunning->getUserManager();
            if ($userManager->performLogin($request->getPost()['username'], $request->getPost()['password'])) {
                return new RedirectResponse('/dashboard');
            }
        }
        return new RedirectResponse('/');
    }

    public function dashboard(RequestEvent $event): TwigResponse
    {
        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        return new TwigResponse('index/dashboard.html.twig', ['authenticatedUser' => $isItRunning->getAuthenticatedUser()]);
    }
}