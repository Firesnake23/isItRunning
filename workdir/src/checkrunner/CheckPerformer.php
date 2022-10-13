<?php

namespace firesnake\isItRunning\checkrunner;

use firesnake\isItRunning\entities\Check;
use firesnake\isItRunning\entities\CheckableEnvironment;

/**
 * This is the interface, which will test and deliver the
 * test result
 */
interface CheckPerformer
{
    public function performCheck(CurlResponse $curlResponse) :bool;
    public function getName(): string;

    /**
     * Configure your check with the
     * provided environment variables.
     *
     * If you need a Variable, let the use know by letting the check
     * fail, and write a comment what went wrong.
     *
     * @param string $config
     * @param CheckableEnvironment $environment
     */
    public function configure(string $config, CheckableEnvironment $environment);

    /**
     * Used only when the check fails
     * Intended to provide an explanation why the check failed
     * @return string|null
     */
    public function getComment(): ?string;
}