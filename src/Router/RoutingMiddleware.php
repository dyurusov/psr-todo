<?php

namespace App\Router;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RoutingMiddleware implements MiddlewareInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->match($request);
        if ($route->isMatched()) {
            foreach ($route->getAttributes() as $key => $val) {
                $request = $request->withAttribute($key, $val);
            }
            return $route->getHandler()->handle($request);
        }
        return $handler->handle($request);
    }
}