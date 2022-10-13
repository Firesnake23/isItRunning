<?php

namespace firesnake\isItRunning\routing;

interface UrlParameterDefinition extends \JsonSerializable
{
    /**
     * The type of this param
     * @return string
     */
    public function getType(): string;

    /**
     * The value of the param
     * @return mixed
     */
    public function getValue(): mixed;
}