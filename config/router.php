<?php

$router = $container->get(App\Router\RouterInterface::class);

$router->addRoute('get', App\Actions\HomeAction::class, '/');
$router->addRoute('get', App\Actions\TaskActions\IndexAction::class, '/tasks');
$router->addRoute('get', App\Actions\TaskActions\UpdateFormAction::class, '/tasks/{id}/update');
$router->addRoute('post', App\Actions\TaskActions\UpdateAction::class, '/tasks/{id}/update');
$router->addRoute('get', App\Actions\TaskActions\CreateFormAction::class, '/tasks/create');
$router->addRoute('post', App\Actions\TaskActions\CreateAction::class, '/tasks/create');
$router->addRoute('get', App\Actions\Auth\LoginFormAction::class, '/login');
$router->addRoute('post', App\Actions\Auth\LoginAction::class, '/login');
$router->addRoute('post', App\Actions\Auth\LogoutAction::class, '/logout');

return $router;