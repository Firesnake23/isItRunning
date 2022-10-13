<?php

namespace firesnake\isItRunning\checkrunner;

use firesnake\isItRunning\entities\Check;
use firesnake\isItRunning\entities\CheckableEnvironment;

/**
 * This is the interface, which will test and deliver the
 * testresult
 */
interface CheckPerformer
{
    public function performCheck(CurlResponse $curlResponse) :bool;
    public function getName(): string;
    public function configure(string $config, CheckableEnvironment $environment);
    public function getComment(): ?string;
}