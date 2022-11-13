<?php

namespace firesnake\isItRunning\routing;

use firesnake\isItRunning\IsItRunning;
use firesnake\isItRunning\reflection\ClassFinder;

class RouteCache
{
    private const CACHE_PATH =  __DIR__ . '/../../cache/';
    private const CACHE_FILENAME = 'routing.json';
    private const CACHE_FULL_PATH = self::CACHE_PATH . self::CACHE_FILENAME;

    /** @var Route[] */private array $cache = [];
    private $urlMapping = [];
    private IsItRunning $isItRunning;

    public function __construct(IsItRunning $isItRunning)
    {
        $this->isItRunning = $isItRunning;
        $this->load();
    }

    public function getUrlMap()
    {
        return $this->urlMapping;
    }

    private function buildCache(): void
    {
        $this->ensureCacheDirExists();
        $classFinder = new ClassFinder(Route::class);

        $classes = $classFinder->findClasses();
        $this->cache['routes'] = [];
        foreach($classes as $class) {
            if($class == CachedRoute::class) {
                continue;
            }

            /** @var Route $route */
            $route = new $class();
            $this->cache['routes'][] = new CachedRoute(
                $route->getUrl(),
                $route->getAliases(),
                $route->getParameterDefinitions(),
                $route->getController(),
                $route->getMethod(),
                $route->protectedBy()
            );
        }

        $this->buildUrlMap();

        $this->cache['urlMap'] = $this->urlMapping;
    }

    private function buildUrlMap()
    {
        /*
         * Maybe it is possible to cache the whole url map, so this does not need to be rebuild everytime
         * a request occurs.
        */
        foreach($this->cache['routes'] as $route) {
            if(!isset($this->urlMapping[$route->getUrl()])) {
                $this->urlMapping[$route->getUrl()] = $route;
            }
            // how to issue a warning, when a route is a duplicate? Possibly even redirect ro route?

            foreach ($route->getAliases() as $alias) {
                //this would be better if a redirect to the correct route is possible...
                if(!isset($this->urlMapping[$alias])) {
                    $this->urlMapping[$alias] = $route;
                }
            }
        }
    }

    private function load()
    {
        if(!file_exists(self::CACHE_FULL_PATH)) {
            $this->buildCache();
            $this->flush();
            $this->cache = [];
            $this->load();
            return;
        }

        //load from cache
        $cacheContent = json_decode(file_get_contents(self::CACHE_FULL_PATH), true);
        $i = 0;
        foreach ($cacheContent['routes'] as $cachedRoute) {
            $protectedBy = [];
            foreach($cachedRoute['protection'] as $classname) {
                $reflectionClass = new \ReflectionClass($classname);
                $protectedBy[] = $reflectionClass->newInstance($this->isItRunning);
            }

            $parsed = new CachedRoute(
                $cachedRoute['baseroute'],
                $cachedRoute['aliases'],
                [],
                $cachedRoute['controller'],
                $cachedRoute['method'],
                $protectedBy
            );
            $this->cache[] = $parsed;
        }

        $this->urlMapping = $cacheContent['urlMap'];

    }

    private function flush()
    {
        $stream = fopen(self::CACHE_FULL_PATH,'w');
        fwrite($stream, json_encode($this->cache));
        fclose($stream);
    }

    private function ensureCacheDirExists():void
    {
        $pathInfo = explode('/', self::CACHE_PATH);
        $path = '/';
        foreach($pathInfo as $part) {

            if($path != '/') {
                $path .= '/';
            }

            $path .= $part;

            if(!file_exists($path)) {
                mkdir($path);
            }

        }
    }
}