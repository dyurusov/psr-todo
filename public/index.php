<?php

use App\QueueRequestHandler;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use DI\ContainerBuilder;


chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// use container development mode for simplicity
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    App\Template\TemplateEngineInterface::class => DI\create(App\Template\TemplateEngine::class)
        ->constructor('views'),
]);
$container = $containerBuilder->build();


$app = new QueueRequestHandler($container->get(App\Error\ErrorHandler::class));

//// Add one or more middleware:
//$app->add(new AuthorizationMiddleware());
//$app->add(new RoutingMiddleware());

$response = $app->handle(ServerRequestFactory::fromGlobals());

$emitter = new SapiEmitter();
$emitter->emit($response);
