<?php

namespace firesnake\isItRunning\checkrunner;

use DateTime;
use Doctrine\ORM\EntityManager;
use firesnake\isItRunning\entities\Check;
use firesnake\isItRunning\entities\CheckableEnvironment;
use firesnake\isItRunning\entities\CheckResult;
use firesnake\isItRunning\entities\EnvironmentResult;

class CheckRunner
{
    private CheckableEnvironment $environment;
    private EntityManager $entityManager;

    public function __construct(CheckableEnvironment $environment, EntityManager $entityManager)
    {
        $this->environment = $environment;
        $this->entityManager = $entityManager;
    }

    public function run()
    {
        $checks = $this->environment->getChecks();
        $environmentResult = new EnvironmentResult();
        $environmentResult->setCheckableEnvironment($this->environment);
        $environmentResult->setPerformed(new DateTime());

        foreach($checks as $check) {
            $this->performCheck($environmentResult, $check);
        }

        $this->entityManager->flush();
    }

    private function performCheck(EnvironmentResult $environmentResult, Check $check)
    {
        $checkResult = new CheckResult();
        $checkResult->setEnvironmentResult($environmentResult);

       $curlResult = $this->curl($check);

        $checker = $check->getChecker();

        try {
            $checkerClass = new \ReflectionClass($checker);
            /** @var CheckPerformer $checkerInstance */
            $checkerInstance = $checkerClass->newInstance();
            $checkerInstance->configure($check->getRunnerConfig(), $this->environment);
            $checkPassed = $checkerInstance->performCheck($curlResult);
            $checkResult->setPassed($checkPassed);
            $checkResult->setComment($checkerInstance->getComment());
            $checkResult->setCheck($check);
            return $checkPassed;
        } catch (\ReflectionException $e) {
            $checkResult->setPassed(false);
            $checkResult->setComment($e->getMessage());
        } finally {
            $this->entityManager->persist($checkResult);
        }
        return false;
    }

    private function curl(Check $check): CurlResponse
    {
        $checkUrl = $check->getUrl();
        $hasParams = str_contains($checkUrl, '{{');

        //fill params
        if($hasParams) {
            $params = $check->getUrlParams();

            $envVars = $this->environment->getEnvVarsAssoc();
            foreach ($params as $param) {
                if(isset($envVars[$param])) {
                    $checkUrl = str_replace('{{' . $param . '}}', $envVars[$param]->getValue(), $checkUrl);
                }
            }
        }

        // curl
        $curl = new Curl();
        $curl->addOption(CURLOPT_URL, $checkUrl);

        if($check->isUseHeaders()) {
            $curl->showHeaders();
        }
        $curl->exec();
        return $curl->getResponse();
    }
}