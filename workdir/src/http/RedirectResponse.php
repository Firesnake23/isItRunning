<?php

namespace firesnake\isItRunning\http;

class RedirectResponse extends AbstractResponse
{
    private string $location;

    public function __construct(string $location) {
        $this->location = $location;
    }

    public function send(): void
    {
        header('location: ' . $this->location);
    }
}