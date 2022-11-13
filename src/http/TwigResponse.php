<?php

namespace firesnake\isItRunning\http;

use firesnake\isItRunning\IsItRunning;

class TwigResponse extends AbstractResponse
{
    private array $templateParams = [];
    private string $template;

    public function __construct(string $template, array $templateParams)
    {
        $this->template = $template;
        $this->templateParams = $templateParams;
    }

    public function send(): void
    {
        $event = $this->getEvent();
        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        $twigEnv = $isItRunning->getTwigEnvironment();
        echo $twigEnv->render($this->template, $this->templateParams);
    }
}