<?php

use App\QueueRequestHandler;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use App\Router\RoutingMiddleware;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$config = require 'config/config.php';
$container = require 'config/container.php';
$router = require 'config/router.php';

$app = new QueueRequestHandler($container->get(App\Error\ErrorHandler::class));
$app->add(new RoutingMiddleware($router));

$request = ServerRequestFactory::fromGlobals();
$response = $app->handle($request);
(new SapiEmitter())->emit($response);
