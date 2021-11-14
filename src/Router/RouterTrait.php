<?php

namespace App\Router;

trait RouterTrait
{
    protected RouterInterface $router;

    protected function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    protected function generateUrl(string $routeName, array $params = [], array $queryParams = []): string
    {
        return $this->router->generateUrl($routeName, $params,  $queryParams);
    }
}
