<?php

namespace App\Router;

use Psr\Http\Message\ServerRequestInterface;
use Aura\Router\RouterContainer;
use Psr\Container\ContainerInterface;


class Router implements RouterInterface
{
    private ContainerInterface $container;
    private RouterContainer $routerContainer;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        // TODO: implement baseUrl
        $this->routerContainer = new RouterContainer();
    }

    public function getMap()
    {
        return $this->routerContainer->getMap();
    }

    public function match(ServerRequestInterface $request): RouteInterface
    {
        $matcher = $this->routerContainer->getMatcher();
        $matched = $matcher->match($request);
        return $matched
            ? new Route($matched, $this->container->get($matched->name))
            : new Route();
    }
}