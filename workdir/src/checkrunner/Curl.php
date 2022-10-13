<?php

namespace firesnake\isItRunning\checkrunner;

class Curl
{
    private $curlHandle;
    private CurlResponse $curlResponse;

    public function __construct(){
        $this->curlHandle = curl_init();
        $this->addOption(CURLOPT_RETURNTRANSFER, true);
    }

    public function showHeaders()
    {
        $this->addOption(CURLOPT_HEADER, true);;
    }

    public function addOption(int $option, mixed $value)
    {
        curl_setopt($this->curlHandle, $option, $value);
    }

    public function exec() {
        $response = curl_exec($this->curlHandle);
        $this->curlResponse = new CurlResponse($response);
    }

    public function getResponse() :CurlResponse
    {
        curl_close($this->curlHandle);
        return $this->curlResponse;
    }
}