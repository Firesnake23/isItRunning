<?php

namespace firesnake\isItRunning\controllers;

use firesnake\isItRunning\entities\EnvironmentResult;
use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\http\TextResponse;
use firesnake\isItRunning\http\Twig404Response;
use firesnake\isItRunning\http\TwigResponse;
use firesnake\isItRunning\IsItRunning;

class ResultController
{
    public function overview(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!isset($request->getGet()['q'])) {
            return new RedirectResponse('/dashboard');
        }

        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        $em = $isItRunning->getEntityManager();

        /** @var EnvironmentResult[] $envResults */
        $envResults = $em->getRepository(EnvironmentResult::class)->findBy([
            'checkableEnvironment' => $request->getGet()['q']
        ]);

        $envResults = array_reverse($envResults);

        $page = 1;
        if(isset($request->getGet()['p'])) {
            $page = $request->getGet()['p'];
        }

        $maxPages = ceil(count($envResults) / 30);
        $envResults = array_splice($envResults, ($page - 1) * 30, 30);


        $statusMap = [];
        $failedResults=[];

        foreach ($envResults as $envResult) {
            $status = true;
            $tempList=[];
            $checkResults = $envResult->getCheckResults();
            foreach($checkResults as $checkResult) {
                if(!$checkResult->isPassed()){
                    $status = false;
                    $tempList[] = $checkResult;
                }
            }
            $failedResults[$envResult->getId()] = $tempList;
            $statusMap[$envResult->getId()] = $status;
        }

        return new TwigResponse('/result/overview.html.twig', [
            'results' => $envResults,
            'statusMap' => $statusMap,
            'maxPages' => $maxPages,
            'page' => $page,
            'authenticatedUser' => $isItRunning->getAuthenticatedUser(),
            'checkResults' => $failedResults
        ]);
    }

    public function checkResult(RequestEvent $event) {
        $request = $event->getRequest();
        if (!isset($request->getGet()['q'])) {
            return new RedirectResponse('/dashboard');
        }

        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');

        /** @var EnvironmentResult $envResult */
        $envResult = $isItRunning->getEntityManager()->getRepository(EnvironmentResult::class)->find($request->getGet()['q']);

        if($envResult == null) {
            return new Twig404Response( 'result/checkResultNotFound.html.twig', [
                'authenticatedUser' => $isItRunning->getAuthenticatedUser()
            ]);
        }

        $checkResults = $envResult->getCheckResults();

        return new TwigResponse('result/checkResult.html.twig', [
            'authenticatedUser' => $isItRunning->getAuthenticatedUser(),
            'checkResults' => $checkResults
        ]);
    }
}