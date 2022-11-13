<?php

namespace firesnake\isItRunning\http;

interface Response
{
    public function send(): void;
}