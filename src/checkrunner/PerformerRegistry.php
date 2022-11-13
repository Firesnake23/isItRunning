<?php

namespace firesnake\isItRunning\checkrunner;

class PerformerRegistry
{
    private $performers = [];

    public function __construct()
    {
        $this->addPerformer(new HttpStatusTest());
    }

    public function addPerformer(CheckPerformer $performer)
    {
        if(!isset($this->performers[$performer->getName()])) {
            $this->performers[$performer->getName()] = $performer;
        }
    }

    /**
     * @return CheckPerformer[]
     */
    public function getPerformers(): array
    {
        return array_values($this->performers);
    }
}