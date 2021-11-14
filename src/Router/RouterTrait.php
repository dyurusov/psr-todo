<?php

namespace App\Router;

use App\Actions\Auth\LoginFormAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\HomeAction;

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

    protected function getRouterRenderParams(): array
    {
        return [
            'urls' => [
                'home' => $this->generateUrl(HomeAction::class),
                'loginForm' => $this->generateUrl(LoginFormAction::class),
                'logout' => $this->generateUrl(LogoutAction::class),
            ],
        ];
    }
}
