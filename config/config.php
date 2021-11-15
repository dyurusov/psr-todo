<?php

// TODO: use dotenv
return [
    'db' => [
        'dsn' => 'sqlite:db/db.sqlite',
        'user' => null,
        'pass' => null,
        'useFixture' => false,
        'scripts' => require 'db.php',
    ],
    'viewsPath' => 'views',
    'baseUrl' => '',
];