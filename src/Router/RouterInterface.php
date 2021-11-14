<?php

namespace App\Router;

use Psr\Http\Message\ServerRequestInterface;


interface RouterInterface
{
    public function match(ServerRequestInterface $request): RouteInterface;
//    public function route(string $name, string $path, callable $handler, array)
}