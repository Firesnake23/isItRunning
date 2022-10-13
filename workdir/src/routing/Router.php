<?php

namespace firesnake\isItRunning\routing;

use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\IsItRunning;

class Router
{
    private RouteCache $routeCache;
    private IsItRunning $isItRunning;

    public function __construct(IsItRunning $isItRunning)
    {
        $this->routeCache = new RouteCache($isItRunning);
        $this->isItRunning = $isItRunning;
    }

    public function handleRequest(Request $request) {
        $route = $this->findRoute($request->getUri());
        if($route !== null) {
            //fire request event, and then execute middleware according to config
            $requestEvent = new RequestEvent($request, $route->getController() . '::' . $route->getMethod());
            $this->isItRunning->getEventHandler()->fire($requestEvent);
            $requestEvent->getResponse()?->send();
            return;
        }

        $response = new RedirectResponse('/index');
        $response->send();
    }

    private function findRoute(string $uri) :?Route
    {
        $urlMap = $this->routeCache->getUrlMap();
        if(isset($urlMap[$uri])) {
            //validate params

            //performChecks
            /** @var Route $route */
            $route = $urlMap[$uri];

            $permissions = $route['protection'];
            foreach ($permissions as $permissionClass) {

                $reflectionClass = new \ReflectionClass($permissionClass);
                /** @var RoutePermission $permission */
                $permission = $reflectionClass->newInstance($this->isItRunning);

                //return if a protection level is not matched
                if(!$permission->checkPermission()) {
                    return null;
                }
            }
            return new CachedRoute(
                $route['baseroute'],
                $route['aliases'],
                $route['parameterDefinitions'],
                $route['controller'],
                $route['method'],
                $route['protection']
            );
        }

        return null;
    }
}