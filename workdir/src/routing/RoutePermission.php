<?php

namespace firesnake\isItRunning\routing;

interface RoutePermission
{
    /**
     * A Systemwide unique name for a route protector.
     * Must return the same string, every time
     * @return string
     */
    public function getName(): string;

    /**
     * A human readable name
     * @return string
     */
    public function getDisplayName(): string;

    /**
     * @return int - The error code when a route permission is not met
     */
    public function errorCode(): int;

    public function checkPermission(): bool;
}