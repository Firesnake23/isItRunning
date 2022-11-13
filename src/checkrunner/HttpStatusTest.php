<?php

namespace firesnake\isItRunning\checkrunner;

use firesnake\isItRunning\entities\CheckableEnvironment;

class HttpStatusTest implements CheckPerformer
{

    private string $config;
    private ?string $comment = null;

    public function performCheck(CurlResponse $curlResponse): bool
    {
        $statusLine = $curlResponse->getStatusLine();
        if($statusLine == null) {
            return false;
        }

        $statusCode = explode(' ', $statusLine)[1];

        if($statusCode == $this->config) {
            return true;
        }

        $this->comment = 'Status code is ' . $statusCode . ' instead of ' . $this->config;
        return false;
    }

    public function getName(): string
    {
        return "Check Status";
    }

    public function configure(string $config, CheckableEnvironment $environment)
    {
        $this->config = $config;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}