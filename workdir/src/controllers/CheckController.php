<?php

namespace firesnake\isItRunning\controllers;

use firesnake\isItRunning\entities\Check;
use firesnake\isItRunning\entities\CheckableEnvironment;
use firesnake\isItRunning\entities\User;
use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\http\Response;
use firesnake\isItRunning\http\TextResponse;
use firesnake\isItRunning\http\TwigResponse;
use firesnake\isItRunning\IsItRunning;
use firesnake\isItRunning\managers\CheckManager;

class CheckController
{
    public function overview(RequestEvent $event): TwigResponse
    {
        $checks = $this->getCheckManager($event)->listChecks();
        return new TwigResponse('check/overview.html.twig', [
            'list' => $checks,
            'authenticatedUser' => $this->getAuthenticatedUser($event)
        ]);
    }

    public function create(RequestEvent $event): TwigResponse
    {
        $performerInstances = $this->getPerformers($event->getParam('isItRunning'));

        return new TwigResponse('check/create.html.twig', [
            'checkRunners' => $performerInstances,
            'authenticatedUser' => $this->getAuthenticatedUser($event)
        ]);
    }

    public function save(RequestEvent $event): Response
    {
        //validate request
        $request = $event->getRequest();
        if(isset($request->getPost()['name']) && isset($request->getPost()['url']) && isset($request->getPost()['useHeaders'])) {
            $checkManager = $this->getCheckManager($event);

            $check = new Check();

            if(isset($request->getPost()['id'])) {
                $existing = $checkManager->getCheckById($request->getPost()['id']);
                if($existing !== null) {
                    $check = $existing;
                }
            }

            $check->setName($request->getPost()['name']);
            $check->setUrl($request->getPost()['url']);
            $check->setUseHeaders($request->getPost()['useHeaders']);
            $check->setOwner($this->getAuthenticatedUser($event));

            if(isset($request->getPost()['runnerConfig'])) {
                $check->setRunnerConfig($request->getPost()['runnerConfig']);
            }

            $check->setChecker($request->getPost()['checker']);
            $checkManager->saveCheck($check);
        }

        return new RedirectResponse('/checks');
    }

    public function delete(RequestEvent $event): RedirectResponse
    {
        $request = $event->getRequest();
        if(isset($request->getGet()['q'])) {
            /** @var IsItRunning $isItRunning */
            $isItRunning = $event->getParam('isItRunning');
            $em = $isItRunning->getEntityManager();
            $check = $em->getRepository(Check::class)->find($request->getGet()['q']);
            if($check !== null) {
                $em->remove($check);
                $em->flush();;
            }
        }
        return new RedirectResponse('/checks');
    }

    public function edit(RequestEvent $event) {
        $request = $event->getRequest();
        if(isseT($request->getGet()['q'])) {
            $checkManager = $this->getCheckManager($event);
            $check = $checkManager->getCheckById($request->getGet()['q']);

            /** @var IsItRunning $isItRunning */
            $isItRunning = $event->getParam('isItRunning');
            $environmentManager = $isItRunning->getEnvironmentManager();

            $environments = $environmentManager->listEnvironments();

            if($check != null) {
                return new TwigResponse('check/edit.html.twig', [
                    'check'  => $check,
                    'checkRunners' => $this->getPerformers($isItRunning),
                    'environments' => $environments,
                    'authenticatedUser' => $this->getAuthenticatedUser($event)
                ]);
            }
        }

        return new RedirectResponse('/checks');
    }

    public function toggleEnv(RequestEvent $event) {
        $request = $event->getRequest();
        if(isset($request->getPost()['env']) && isset($request->getPost()['check'])) {
            /** @var IsItRunning $isItRunning */
            $isItRunning = $event->getParam('isItRunning');

            $environmentManager = $isItRunning->getEnvironmentManager();
            $checkManager = $this->getCheckManager($event);

            $check = $checkManager->getCheckById($request->getPost()['check']);
            $env = $environmentManager->getEnvironmentById($request->getPost()['env']);

            if($check != null && $env !== null) {
                $checkManager->toggleEnvironment($check, $env);
                http_send_status(200);
                return new TextResponse('');
            }
        }
        http_send_status(400);
        return new TextResponse('Data not found');
    }

    private function getPerformers(IsItRunning $isItRunning)
    {
        return $isItRunning->getPerformerRegistry()->getPerformers();
    }

    private function getCheckManager(RequestEvent $event):CheckManager
    {
        $request = $event->getRequest();
        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        return $isItRunning->getCheckManager();
    }

    private function getAuthenticatedUser(RequestEvent $event) :?User
    {
        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        return $isItRunning->getAuthenticatedUser();
    }
}