<?php

use App\QueueRequestHandler;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use DI\ContainerBuilder;
use App\Router\RoutingMiddleware;


chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// use container development mode for simplicity
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    App\Template\TemplateEngineInterface::class => DI\create(App\Template\TemplateEngine::class)
        ->constructor('views'),
    App\Router\RouterInterface::class => DI\create(App\Router\Router::class)
        ->constructor(DI\get(Psr\Container\ContainerInterface::class)),
]);
$container = $containerBuilder->build();

$app = new QueueRequestHandler($container->get(App\Error\ErrorHandler::class));

//// Add one or more middleware:
//$app->add(new AuthorizationMiddleware());

// routing
$router = $container->get(App\Router\RouterInterface::class);
$map = $router->getMap();
$map->get(App\Actions\IndexAction::class, '/');
$map->get(App\Actions\ReadAction::class, '/{id}');
$app->add(new RoutingMiddleware($router));

$response = $app->handle(ServerRequestFactory::fromGlobals());

$emitter = new SapiEmitter();
$emitter->emit($response);
