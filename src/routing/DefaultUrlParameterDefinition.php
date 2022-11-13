<?php

namespace firesnake\isItRunning\routing;

class DefaultUrlParameterDefinition implements UrlParameterDefinition
{

    private string $type;
    private mixed $value;

    public function __construct(string $type, $value = null) {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function jsonSerialize(): mixed
    {
        return ['type' => $this->getType(), 'value' => $this->value ?? 'null'];
    }
}