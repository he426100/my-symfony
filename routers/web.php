<?php
$app->group('', function () use ($app) {
    $app->get('/', '\App\Http\Controllers\Welcome:index');
    $app->get('/order', '\App\Http\Controllers\Welcome:order');
    $app->get('/pay', '\App\Http\Controllers\Welcome:pay');
    $app->get('/user', '\App\Http\Controllers\Welcome:user');
    $app->get('/charge', '\App\Http\Controllers\Welcome:charge');
})->add(new \App\Middlewares\Wechat($container));
