<?php

namespace firesnake\isItRunning\controllers;

use firesnake\isItRunning\entities\EnvironmentResult;
use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\http\Response;
use firesnake\isItRunning\http\TextResponse;
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
        $envManager = $isItRunning->getEnvironmentManager();

        $environments = $envManager->listEnvironments();

        $stats = [];
        foreach($environments as $environment) {
            $results = $environment->getEnvironmentResults();
            /** @var EnvironmentResult[] $resultsOrdered */
            $resultsOrdered = array_reverse($results);
            $status = true;
            $streak = 0;
            if(count($resultsOrdered) > 0) {
                $checkResults = $resultsOrdered[0]->getCheckResults();
                foreach($checkResults as $result) {
                    $status = $status && $result->isPassed();
                }

                $streakFound = false;
                foreach($resultsOrdered as $result) {
                    $checkResults = $result->getCheckResults();
                    $singleResult = true;
                    foreach($checkResults as $checkResult) {
                        $singleResult = $singleResult && $checkResult->isPassed();
                    }

                    if($status == $singleResult && !$streakFound) {
                        $streak++;
                    }

                    if($status != $singleResult && !$streakFound) {
                        $streakFound = true;
                    }
                }
            }


            $stats[$environment->getId()] = [
                'runCount' => count($results),
                'status' => $status,
                'streak' => $streak
            ];
        }


        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        return new TwigResponse('index/dashboard.html.twig', [
            'authenticatedUser' => $isItRunning->getAuthenticatedUser(),
            'environments' => $environments,
            'stats' => $stats
        ]);
    }

    public function libJs(RequestEvent $event) : Response {
        $path = __DIR__ . '/../../public/js/lib.js';
        $file = file_get_contents($path);
        header('content-type:application/javascript');
        return new TextResponse($file);
    }
}