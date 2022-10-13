<?php

namespace firesnake\isItRunning\checkrunner;

use firesnake\isItRunning\utils\StringUtils;

class CurlResponse
{
    private ?string $statusLine = null;
    private array $headers = [];
    private string $body = '';

    public function __construct(string $response) {
        $lines = explode(PHP_EOL, $response);

        $headers = false;
        if(stripos($lines[0], 'http/') !== false) {
            $this->statusLine = trim($lines[0]);
            $headers = true;
        }
        $i = 0;
        for($i = 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            $header = explode(':', $line);

            if(trim($lines[$i]) == '') {
                $i++;
                break;
            }

            $headerContent = '';
            for($j = 1; $j < count($header); $j++) {
                $headerContent .= $header[$j];
            }
            $this->headers[$header[0]] = $headerContent;
        }

        for($k = $i; $k < count($lines); $k++) {
            $line = trim($lines[$k]);
            if($this->body != '') {
                $this->body .= PHP_EOL;
            }
            $this->body .= $line;
        }
    }

    /**
     * @return string|null
     */
    public function getStatusLine(): ?string
    {
        return $this->statusLine;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}