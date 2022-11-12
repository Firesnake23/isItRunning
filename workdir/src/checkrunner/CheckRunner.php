<?php

namespace firesnake\isItRunning\checkrunner;

use DateTime;
use Doctrine\ORM\EntityManager;
use firesnake\isItRunning\entities\Check;
use firesnake\isItRunning\entities\CheckableEnvironment;
use firesnake\isItRunning\entities\CheckResult;
use firesnake\isItRunning\entities\EnvironmentResult;
use firesnake\isItRunning\IsItRunning;
use firesnake\isItRunning\managers\EnvironmentManager;
use PHPMailer\PHPMailer\PHPMailer;

class CheckRunner
{
    private CheckableEnvironment $environment;
    private EntityManager $entityManager;
    private IsItRunning $isItRunning;

    public function __construct(CheckableEnvironment $environment, EntityManager $entityManager, IsItRunning $isItRunning)
    {
        $this->environment = $environment;
        $this->entityManager = $entityManager;
        $this->isItRunning = $isItRunning;
    }

    public function run()
    {
        $checks = $this->environment->getChecks();
        $environmentResult = new EnvironmentResult();
        $environmentResult->setCheckableEnvironment($this->environment);
        $environmentResult->setPerformed(new DateTime());

        $passed = true;
        foreach($checks as $check) {
            $checkResult = $this->performCheck($environmentResult, $check);
            $passed = $passed && $checkResult;
        }

        $envManager = $this->isItRunning->getEnvironmentManager();
        $lastResult = $envManager->getLastResult($this->environment);

        $this->entityManager->flush();

        $lastResultPassed = $lastResult->passed();

        if($lastResultPassed !== $passed) {
            $phpmailer = new PHPMailer();

            $phpmailer->isSMTP();
            $phpmailer->Host = getenv('MAIL_HOST');
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = getenv('MAIL_USERNAME');
            $phpmailer->Password = getenv('MAIL_PASSWORD');
            $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $phpmailer->isHTML();
            $phpmailer->Port = getenv('MAIL_PORT');

            $targetMail = $this->environment->getOwner()->getMail();
            $phpmailer->setFrom(getenv('MAIL_FROM'));
            $phpmailer->addAddress($targetMail);

            $phpmailer->Subject = 'Status change in ' . $this->environment->getName();

            $phpmailer->Body = '
                Status changed from ' .
                ($lastResultPassed ? 'OK': 'FAILED') .
                ' to ' .
                ($passed ? 'OK' : 'FAILED') .
                '<br> See details at http://' .
                $_SERVER['SERVER_NAME']. '/checkResult?q=' . $environmentResult->getId()
            ;

            $phpmailer->send();

        }

    }

    private function performCheck(EnvironmentResult $environmentResult, Check $check): bool
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