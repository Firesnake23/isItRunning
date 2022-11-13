<?php

use firesnake\isItRunning\checkrunner\CheckRunner;
use firesnake\isItRunning\entities\CheckableEnvironment;
use firesnake\isItRunning\IsItRunning;

require_once __DIR__ . '/../bootstrap.php';

$em = getEntityManager();

/** @var CheckableEnvironment[] $environments */
$environments = $em->getRepository(CheckableEnvironment::class)->findAll();

$isItRunning = new IsItRunning($em, null);

foreach($environments as $env) {
    $runner = new CheckRunner($env, $em, $isItRunning);

    $cronExpression = new \Cron\CronExpression($env->getSamplingRate());
    if($cronExpression->isDue()) {
        $runner->run();
    }
}