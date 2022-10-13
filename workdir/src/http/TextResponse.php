<?php

namespace firesnake\isItRunning\http;

class TextResponse extends AbstractResponse
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function send(): void
    {
        echo $this->text;
    }
}