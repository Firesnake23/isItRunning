<?php

use firesnake\isItRunning\checkrunner\CheckRunner;
use firesnake\isItRunning\entities\CheckableEnvironment;

require_once __DIR__ . '/../bootstrap.php';

$em = getEntityManager();

/** @var CheckableEnvironment[] $environments */
$environments = $em->getRepository(CheckableEnvironment::class)->findAll();

foreach($environments as $env) {
    $runner = new CheckRunner($env, $em);

    $cronExpression = new \Cron\CronExpression($env->getSamplingRate());
    if($cronExpression->isDue()) {
        $runner->run();
    }
}