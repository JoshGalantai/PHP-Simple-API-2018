<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

// Production would have different settings such as no debug
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'mysql',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci'
        ]
    ]
]);

$container = $app->getContainer();

// Get our connection, queries will be more simple thanks to Illuminate!
$db = new \Illuminate\Database\Capsule\Manager;
$db->addConnection($container['settings']['db']);
$db->setAsGlobal();
$db->bootEloquent();

// Cleaner access to class thanks to autoloader
$booking = new \App\source\Booking($db);

// Separate file for our actual routes, this keeps our code clean!
require __DIR__ . '/../app/routes.php';

$app->get(
    '/', function ($request, $response) {
        return '';
    }
);
