<?php

use DI\ContainerBuilder;

$definitions = [
    App\Template\TemplateEngineInterface::class => DI\create(App\Template\TemplateEngine::class)
        ->constructor($config['viewsPath']),

    App\Router\RouterInterface::class => DI\create(App\Router\Router::class)
        ->constructor(DI\get(Psr\Container\ContainerInterface::class), $config['baseUrl']),

    App\Services\Auth\AuthServiceInterface::class => DI\create(App\Services\Auth\AuthService::class),

    App\Repositories\TaskRepositoryInterface::class => DI\create(App\Repositories\TaskRepository::class)
        ->constructor(DI\get(PDO::class)),

    PDO::class => DI\factory(function ($dsn = null, $user = null, $pass = null, $options = null) use ($config): PDO {
        $dbh = new PDO($dsn, $user, $pass, $options);
        $sqlInit = $config['db']['scripts']['init'] ?? null;
        if ($sqlInit) {
            $dbh->query($sqlInit)->execute();
        }
        $sqlFixture = $config['db']['scripts']['fixture'] ?? null;
        if ($sqlFixture && !empty($config['db']['useFixture'])) {
            $dbh->query($sqlFixture)->execute();
        }
        return $dbh;
    })
        ->parameter('dsn', $config['db']['dsn'])
        ->parameter('user', $config['db']['user'])
        ->parameter('pass', $config['db']['pass']),
];

// use container development mode for simplicity
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions($definitions);
return $containerBuilder->build();
