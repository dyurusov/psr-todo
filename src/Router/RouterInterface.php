<?php

namespace App\Router;

use Psr\Http\Message\ServerRequestInterface;


interface RouterInterface
{
    public function match(ServerRequestInterface $request): RouteInterface;
    public function generateUrl(string $routeName, array $params): string;

    // TODO:
    // public function addRoute(string $name, string $path, callable $handler, array)
}