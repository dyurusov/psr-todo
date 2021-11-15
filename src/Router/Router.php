<?php

namespace App\Router;

use Psr\Http\Message\ServerRequestInterface;
use Aura\Router\RouterContainer;
use Psr\Container\ContainerInterface;


class Router implements RouterInterface
{
    private ContainerInterface $container;
    private RouterContainer $routerContainer;
    private string $baseUrl;

    public function __construct(ContainerInterface $container, string $baseUrl = '')
    {
        $this->container = $container;
        $this->routerContainer = new RouterContainer();
        $this->baseUrl = rtrim($baseUrl, ' /');
    }

    public function match(ServerRequestInterface $request): RouteInterface
    {
        $matcher = $this->routerContainer->getMatcher();
        $matched = $matcher->match($request);
        return $matched
            ? new Route($matched, $this->container->get($matched->name))
            : new Route();
    }

    public function generateUrl(string $routeName, array $params = [], array $queryParams = []): string
    {
        $routeHelper = $this->routerContainer->newRouteHelper();
        $path = $routeHelper($routeName, $params);
        $queryString =  http_build_query($queryParams);
        return $path . (empty($queryString) ? '' : "?$queryString");
    }

    public function addRoute(string $method, string $name, string $path)
    {
        $map = $this->routerContainer->getMap();;
        $map->$method($name, "{$this->baseUrl}$path");
    }
}