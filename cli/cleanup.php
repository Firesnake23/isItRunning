<?php

use firesnake\isItRunning\checkrunner\LogCleanup;
use firesnake\isItRunning\entities\User;
use firesnake\isItRunning\IsItRunning;

require_once __DIR__ . '/../bootstrap.php';
$isItRunning = new IsItRunning(getEntityManager(), null);

$logCleanup = new LogCleanup($isItRunning);
$logCleanup->run();