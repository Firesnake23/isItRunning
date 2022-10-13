<?php

namespace firesnake\isItRunning\routing;

interface Route
{
    /**
     * Return the default route
     * @return string
     */
    public function getUrl(): string;

    /**
     * Return other urls for this route
     * @return array
     */
    public function getAliases(): array;

    /**
     * Provide the params which will be mapped for this route
     * @return UrlParameterDefinition[]
     */
    public function getParameterDefinitions(): array;

    /**
     * The full qualified name of the controller
     * example "firesnake\\isItRunning\\controller\\IndexController"
     * @return string
     */
    public function getController(): string;

    /**
     * The method name of the controller
     * @return string
     */
    public function getMethod(): string;

    /**
     * an array of classes which implement the {@see RoutePermission}
     * @return string[]
     */
    public function protectedBy(): array;
}