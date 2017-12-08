<?php
$appConfig = require __DIR__ . '/../config/app.php';
$dbConfig = require __DIR__ . '/../config/databases.php';
$loggerConfig = require __DIR__ . '/../config/logger.php';
$config = ['settings' => array_merge($appConfig, $dbConfig, $loggerConfig)];
$container = new \Slim\Container($config);
$app = new \Slim\App($container);
//session
$app->add(new \Slim\Middleware\Session([
  //'autorefresh' => true
]));
// Register globally to app
$container['session'] = function ($c) {
    return new \SlimSession\Helper;
};
// Service factory for the ORM
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};
// Service factory for the Logger
$container['logger'] = function ($container) {
    $logger = new \Monolog\Logger($container['settings']['logger']['name']);
    $file_handler = new \Monolog\Handler\StreamHandler($container['settings']['logger']['path']);
    $logger->pushHandler($file_handler);
    return $logger;
};
//error
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        if ($exception->getCode() == 302) {
            return $c['response']->withRedirect($exception->getMessage());
        }
        return $c['response']->withStatus(500)
                             ->withHeader('Content-Type', 'text/html')
                             ->write($exception->getMessage());
    };
};
//view
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('..\app\Views', [
        'cache' => '..\cache'
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};
//中间件

//路由
require __DIR__ . '/../routers/web.php';
require __DIR__ . '/../routers/api.php';

return $app;
