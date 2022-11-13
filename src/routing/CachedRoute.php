<?php

namespace firesnake\isItRunning\routing;

class CachedRoute implements Route, \JsonSerializable
{
    private string $url;
    private array $aliases;
    /** @var UrlParameterDefinition[] */private array $parameterDefinitions;
    private string $controller;
    private string $method;
    private array $protectedBy;

    /**
     * @param string $url
     * @param array $aliases
     * @param UrlParameterDefinition $parameterDefinitions
     * @param string $controller
     * @param string $method
     * @param string[] $protectedBy
     */
    public function __construct(string $url, array $aliases, array $parameterDefinitions, string $controller, string $method, array $protectedBy) {
        $this->url = $url;
        $this->aliases = $aliases;
        $this->parameterDefinitions = $parameterDefinitions;
        $this->controller = $controller;
        $this->method = $method;
        $this->protectedBy = $protectedBy;
    }


    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * @return UrlParameterDefinition[]
     */
    public function getParameterDefinitions(): array
    {
        return $this->parameterDefinitions;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function jsonSerialize(): mixed
    {
        $cachedRoutes = [];
        foreach($this->protectedBy as $permission) {
            $reflecationClass = new \ReflectionClass($permission);
            $cachedRoutes[] = $reflecationClass->getName();
        }

        return [
            'baseroute' => $this->getUrl(),
            'aliases' => $this->getAliases(),
            'parameterDefinitions' => $this->getParameterDefinitions(),
            'controller' => $this->getController(),
            'method' => $this->getMethod(),
            'protection' => $cachedRoutes
        ];
    }

    public function protectedBy(): array
    {
        return $this->protectedBy;
    }
}