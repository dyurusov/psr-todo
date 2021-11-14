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
    App\Services\Auth\AuthServiceInterface::class => DI\create(App\Services\Auth\AuthService::class),
    App\Repositories\TaskRepositoryInterface::class => DI\create(App\Repositories\TaskRepository::class)
        ->constructor(DI\get(PDO::class)),
    PDO::class => DI\factory(function ($dsn = null, $user = null, $pass = null, $options = null): PDO {
        $dbh = new PDO($dsn, $user, $pass, $options);
        $createTableSql = "
            CREATE TABLE IF NOT EXISTS Tasks (
                id INT NOT NULL PRIMARY KEY,
                user VARCHAR(32) NOT NULL,
                email VARCHAR(32) NOT NULL,
                description VARCHAR(255) NOT NULL,
                edited INT,
                done INT
            );
        ";
        $insertSql = "
            INSERT INTO Tasks
                (id, user, email, description, edited, done)
            VALUES
                (1, 'user1', 'email1@example.com', 'description1', 0, 0),
                (2, 'user2', 'email2@example.com', 'description2', 0, 1),
                (11, 'user1', 'email1@example.com', 'description11', 1, 0),
                (12, 'user2', 'email2@example.com', 'description12', 1, 1),
                (31, 'user3', 'email3@example.com', 'description3', 1, 0),
                (32, 'user3', 'email3@example.com', 'description32', null, null),
                (13, 'user1', 'email1@example.com', 'description13', null, null);
        ";
        $dbh->query($createTableSql)->execute();
        $dbh->query($insertSql)->execute();
        return $dbh;
    })->parameter('dsn', 'sqlite::memory:'),
//    })->parameter('dsn', 'sqlite:db.sqlite'),
]);
$container = $containerBuilder->build();

$app = new QueueRequestHandler($container->get(App\Error\ErrorHandler::class));

//// Add one or more middleware:
//$app->add(new AuthorizationMiddleware());

// routing
$router = $container->get(App\Router\RouterInterface::class);
$map = $router->getMap();
$map->get(App\Actions\HomeAction::class, '/');
$map->get(App\Actions\TaskActions\IndexAction::class, '/tasks');
$map->get(App\Actions\Auth\LoginFormAction::class, '/login');
$map->post(App\Actions\Auth\LoginAction::class, '/login');
$map->post(App\Actions\Auth\LogoutAction::class, '/logout');
$app->add(new RoutingMiddleware($router));

$response = $app->handle(ServerRequestFactory::fromGlobals());

$emitter = new SapiEmitter();
$emitter->emit($response);
