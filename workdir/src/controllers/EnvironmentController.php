<?php

namespace firesnake\isItRunning\controllers;

use firesnake\isItRunning\checkrunner\CheckRunner;
use firesnake\isItRunning\entities\CheckableEnvironment;
use firesnake\isItRunning\entities\EnvironmentResult;
use firesnake\isItRunning\entities\User;
use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\http\Response;
use firesnake\isItRunning\http\TwigResponse;
use firesnake\isItRunning\IsItRunning;
use firesnake\isItRunning\managers\EnvironmentManager;
use firesnake\isItRunning\repositories\EnvironmentResultRepository;

class EnvironmentController
{
    public function overview(RequestEvent $event): Response
    {
        $environmentManager = $this->getEnvironmentManager($event);
        $environments = $environmentManager->listEnvironments();

        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        /** @var EnvironmentResultRepository $envResultRepo */
        $envResultRepo = $isItRunning->getEntityManager()->getRepository(EnvironmentResult::class);

        $statusMap = [];

        foreach($environments as $environment) {
            $envResult = $envResultRepo->getLatestResult($environment);
            $passed = true;

            if($envResult != null) {
                $checks = $envResult->getCheckResults();
                foreach($checks as $check) {
                    if(!$check->isPassed()) {
                        $passed = false;
                        break;
                    }
                }
            }

            $statusMap[$environment->getId()] = $passed;
        }

        return new TwigResponse('environment/overview.html.twig', [
            'list' => $environments,
            'authenticatedUser' => $this->getAuthenticatedUser($event),
            'statusMap' => $statusMap
        ]);
    }

    public function delete(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (isset($request->getGet()['q'])) {
            $envManager = $this->getEnvironmentManager($event);

            $checkeableEnv = $envManager->getEnvironmentById($event->getRequest()->getGet()['q']);
            if ($checkeableEnv != null) {
                if (!empty($checkeableEnv->getEnvVars())) {
                    return new RedirectResponse('/environment/edit?q=' . $checkeableEnv->getId());
                }
                $envManager->deleteEnvironment($checkeableEnv);
            }
        }

        return new RedirectResponse('/environments');
    }

    public function create(RequestEvent $event)
    {
        return new TwigResponse('environment/create.html.twig', []);
    }

    public function edit(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (isset($request->getGet()['q'])) {
            $envManager = $this->getEnvironmentManager($event);
            $env = $envManager->getEnvironmentById($request->getGet()['q']);
            if ($env != null) {
                return new TwigResponse('environment/edit.html.twig', [
                    'environment' => $env,
                    'authenticatedUser' => $this->getAuthenticatedUser($event)
                ]);
            }
        }

        return new RedirectResponse('/environments');
    }

    public function save(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (isset($request->getPost()['name']) && isset($request->getPost()['samplingRate'])) {
            $envManager = $this->getEnvironmentManager($event);
            $checkeableEnvironment = new CheckableEnvironment();

            if (isset($request->getPost()['id'])) {
                $checkeableEnvironment = $envManager->getEnvironmentById($request->getPost()['id']);
            }

            $checkeableEnvironment->setName($request->getPost()['name']);;
            $checkeableEnvironment->setSamplingRate($request->getPost()['samplingRate']);
            $checkeableEnvironment->setOwner($this->getAuthenticatedUser($event));


            $envManager->saveEnvironment($checkeableEnvironment);

            return new RedirectResponse('/environments');
        }
        return new RedirectResponse('/');
    }

    public function addVariable(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (
            isset($request->getPost()['id']) &&
            isset($request->getPost()['varName']) &&
            isset($request->getPost()['varValue'])
        ) {
            $envManager = $this->getEnvironmentManager($event);

            $environment = $envManager->getEnvironmentById($request->getPost()['id']);

            if ($environment != null) {
                $envManager->addNewVariable($environment, $request->getPost()['varName'], $request->getPost()['varValue']);
            }
            return new RedirectResponse('/environment/edit?q=' . $environment->getId());
        }
        return new RedirectResponse('/environments');
    }

    public function deleteVariable(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (isset($request->getGet()['q'])) {
            $envManager = $this->getEnvironmentManager($event);
            $envVar = $envManager->getEnvironmentVariableById($request->getGet()['q']);

            if ($envVar != null) {
                $environment = $envVar->getEnvironment();
                $envManager->deleteVariable($envVar);

                return new RedirectResponse('/environment/edit?q=' . $environment->getId());
            }
        }

        return new RedirectResponse('/environments');
    }

    public function runChecks(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (isset($request->getGet()['q'])) {
            $envManager = $this->getEnvironmentManager($event);

            $env = $envManager->getEnvironmentById($request->getGet()['q']);
            if ($env !== null) {
                /** @var IsItRunning $isItRunning */
                $isItRunning = $event->getParam('isItRunning');

                $checkRunner = new CheckRunner($env, $isItRunning->getEntityManager());
                $checkRunner->run();
            }
        }

        return new RedirectResponse('/environments');
    }

    private function getEnvironmentManager(RequestEvent $event): EnvironmentManager
    {
        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        return $isItRunning->getEnvironmentManager();
    }

    private function getAuthenticatedUser(RequestEvent $event) :?User
    {
        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        return $isItRunning->getAuthenticatedUser();
    }
}